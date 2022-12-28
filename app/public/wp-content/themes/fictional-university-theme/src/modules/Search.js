import $ from "jquery";

class Search {
    constructor() {
        // add search HTML first
        this.addSearchHTML();

        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.events();
        this.closeOverlay();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;
    }
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    addSearchHTML() {
        $('body').append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" id="search-term" class="search-term" placeholder="What are you looking for?" autocomplete="off">
                    <i class="fa fa-window-close search-overlay__close"></i>
                </div>
            </div>
            <div class="container">
                <div id="search-overlay__results"></div>
            </div>
        </div>`)
    }

    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            this.typingTimer && clearTimeout(this.typingTimer);

            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultsDiv.html("");
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults() {
        const postsRequest = $.getJSON(`${window.universityData.root_url}/wp-json/wp/v2/posts?search=${this.searchField.val().trim()}`);
        const pagesRequest = $.getJSON(`${window.universityData.root_url}/wp-json/wp/v2/pages?search=${this.searchField.val().trim()}`);

        Promise.all([postsRequest, pagesRequest]).then((values) => {
            console.log(values);

            var combinedResults = [...(values?.[0] || []), ...(values?.[1] || [])];
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">General Information</h2>
            ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No search results were found.</p>'}
            ${combinedResults.map((item) => `<li><a href="${item.link}">${item.title.rendered}</a> ${item?.type == 'post' && item?.authorName ? 'by ' + item.authorName : ''}</li>`).join("")}
            ${combinedResults.length ? '</ul' : ''}
        `);
            this.isSpinnerVisible = false;
        }).catch(() => {
            this.resultsDiv.html('<p>Unexpected error; please try again.</p>')
        })
    }

    keyPressDispatcher(event) {
        if (
            event.keyCode == 83 &&
            !this.isOverlayOpen &&
            !$("input, textarea").is(":focus")
        ) {
            this.openOverlay();
        }
        if (event.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        window.setTimeout(() => {
            this.searchField.trigger('focus');
        }, 301)
        this.isOverlayOpen = true;
    }
    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = true;
    }
}

export default Search;

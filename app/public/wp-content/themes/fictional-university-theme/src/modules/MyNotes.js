import $ from "jquery";

class myNotes {
    constructor() {
        this.events();
    }
    events() {
        $(".delete-note").on("click", this.deleteNote);
    }
    deleteNote() {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', window.universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/124`,
            type: 'DELETE',
            success: (response) => {
                console.log('success');
                console.log(response)
            },
            error: (response) => {
                console.log('Sorry');
                console.log(response);
            }
        })
    }
}

export default myNotes;

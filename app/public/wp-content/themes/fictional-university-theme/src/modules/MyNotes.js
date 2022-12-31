import $ from "jquery";

class myNotes {
    constructor() {
        this.events();
    }
    events() {
        $(".delete-note").on("click", this.deleteNote.bind(this));
        $(".edit-note").on("click", this.editNote.bind(this));
        $(".update-note").on("click", this.updateNote.bind(this));
    }
    editNote(event) {
        const $noteParentEl = $(event.target).parents('li[data-id]');

        if ($noteParentEl.data('state') == 'editable') {
            this.makeNoteReadonly($noteParentEl);
        } else {
            this.makeNoteEditable($noteParentEl);
        }
    }
    makeNoteEditable($noteParentEl) {
        $noteParentEl.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')
        $noteParentEl.find('.note-title-field, .note-body-field').removeAttr('readonly').addClass('note-active-field');
        $noteParentEl.find('.update-note').addClass('update-note--visible');
        $noteParentEl.data('state', 'editable');
    }
    makeNoteReadonly($noteParentEl) {
        $noteParentEl.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')
        $noteParentEl.find('.note-title-field, .note-body-field').attr('readonly', 'readonly').removeClass('note-active-field');
        $noteParentEl.find('.update-note').removeClass('update-note--visible');
        $noteParentEl.data('state', 'cancel');
    }
    deleteNote(event) {
        const $noteParentEl = $(event.target).parents('li[data-id]').first();
        const noteID = $noteParentEl.attr('data-id');

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', window.universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${noteID}`,
            type: 'DELETE',
            success: (response) => {
                console.log('Delete Success', response);
                $noteParentEl.slideUp();
            },
            error: (response) => {
                console.error('Delete Error', response);
            }
        })
    }
    updateNote(event) {
        const $noteParentEl = $(event.target).parents('li[data-id]').first();
        const noteID = $noteParentEl.attr('data-id');
        const ourUpdatedPost = {
            'title': $noteParentEl.find('input.note-title-field').val(),
            'content': $noteParentEl.find('.note-body-field').val(),
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', window.universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${noteID}`,
            type: 'POST',
            data: ourUpdatedPost,
            success: (response) => {
                console.log('Update Success', response);
                this.makeNoteReadonly($noteParentEl);
            },
            error: (response) => {
                console.error('Update Error', response);
            }
        })
    }
}

export default myNotes;

App.Controllers.WikiPagesBaseFormController = Frontend.AppController.extend({
    startup: function() {
        this.$('#wiki-content').markdownEditor({
            preview: true,
            onPreview: function (content, callback) {
                callback( marked(content) );
            },
            height: 600,
            imageUpload: false
        });
        this.$('form').submit(function() {
            this.$('textarea#content').html($('#wiki-content').markdownEditor('content'));
        }.bind(this));
    }
});
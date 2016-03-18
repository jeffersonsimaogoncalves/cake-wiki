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
        this.$('.insert-image-btn').on('click', function(e) {
            var $btn = $(e.currentTarget);
            var markdown = '![' + $btn.data('filename') + '](' + $btn.data('url') + ')';

            var aceEditor = ace.edit($('#wiki-content .md-editor')[0]);
            aceEditor.insert(markdown);
            return false;
        });
    }
});

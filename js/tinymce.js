(function() {
    tinymce.create("tinymce.plugins.kenplayer_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //add new button    
            ed.addButton("kenplayer", {
                title : "Insert KenPlayer Shortcode",
                cmd : "kenplayer_command",
                image : "https://cdn3.iconfinder.com/data/icons/softwaredemo/PNG/32x32/Circle_Green.png"
            });

            //button functionality.
            ed.addCommand("kenplayer_command", function() {
                var selected_text = ed.selection.getContent();
                var return_text = "[kenplayer]" + selected_text + "[/kenplayer]";
                ed.execCommand("mceInsertContent", 0, return_text);
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "KenPlayer Buttons",
                author : "XWPThemes.com",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("kenplayer_button_plugin", tinymce.plugins.kenplayer_button_plugin);
})();
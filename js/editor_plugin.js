(function() {
	tinymce.create('tinymce.plugins.contact_form_mce', {	
   		init :function(ed, url){			
			ed.addCommand('mcecontact_form_mce', function() {
				ed.windowManager.open({
					file :((ajaxurl.indexOf("://") != -1) ? ajaxurl:(location.protocol+'//'+location.host+ajaxurl))+"?action=formcontactwindow",
					width : 400 + ed.getLang('contact_form_mce.delta_width', 0),
					height : 250 + ed.getLang('contact_form_mce.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
            ed.addButton('contact_form_mce', {
            title : 'Insert Contact Form',
			cmd : 'mcecontact_form_mce',
            });
        }
    });
    tinymce.PluginManager.add('contact_form_mce', tinymce.plugins.contact_form_mce);
 
})();
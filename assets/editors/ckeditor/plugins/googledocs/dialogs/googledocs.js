CKEDITOR.dialog.add('googledocs', function (editor) {

	function onChangeSrc() {
		return true;
	}

	var hasFileBrowser = !!( editor.config.filebrowserImageBrowseUrl || editor.config.filebrowserBrowseUrl ),
		srcBoxChildren = [
			{
	            type: 'text',
				id: 'txtUrl',
	            label: editor.lang.googledocs.url,
	            required: true,
				onChange: onChangeSrc,
	            validate: CKEDITOR.dialog.validate.notEmpty( editor.lang.googledocs.alertUrl )
			}
		];

	if ( hasFileBrowser ) {
		srcBoxChildren.push( {
			type: 'button',
			id: 'browse',
			style: 'display:inline-block;margin-top:16px;',
			align: 'center',
			label: editor.lang.common.browseServer,
			hidden: true,
			filebrowser: 'settingsTab:txtUrl'
		} );
	}

  return {
    title: editor.lang.googledocs.title,
    width: 400,
    height: 150,

    contents:
    [
      //  document settings tab
      {
        id: 'settingsTab',
        label: editor.lang.googledocs.settingsTab,
        elements:
        [
          //  url
          {
			type: 'vbox',
			padding: 0,
			children: [
				{
					type: 'hbox',
					widths: [ '100%' ],
					children: srcBoxChildren
				}
			]
          },
          //  options
          {
            type: 'hbox',
            widths: [ '60px', '330px' ],
            className: 'googledocs',
            children:
            [
              //  width
              {
                type: 'text',
                width: '45px',
                id: 'txtWidth',
                label: editor.lang.common.width,
                'default': 710,
                required: true,
                validate: CKEDITOR.dialog.validate.integer( editor.lang.googledocs.alertWidth )
              },
              //  height
              {
                type: 'text',
                id: 'txtHeight',
                width: '45px',
                label: editor.lang.common.height,
                'default': 920,
                required: true,
                validate: CKEDITOR.dialog.validate.integer( editor.lang.googledocs.alertHeight )
              }
            ]
          }
        ]
      },
      //  upload tab
      {
        id: 'uploadTab',
        label: editor.lang.googledocs.uploadTab,
        filebrowser: 'uploadButton',
        elements:
        [
          //  file input
          {
            type: 'file',
            id: 'upload'
          },
          //  submit button
          {
            type: 'fileButton',
            id: 'uploadButton',
            label: editor.lang.googledocs.btnUpload,
            filebrowser: {
              action: 'QuickUpload',
              target: 'settingsTab:txtUrl'
            },
            'for': [ 'uploadTab', 'upload' ]
          }
        ]
      }
    ],
    onOk: function() {
		var dialog = this;
		var iframe = editor.document.createElement( 'iframe' );
		var txtUrl = dialog.getValueOf( 'settingsTab', 'txtUrl' );
		var regexp = /(ftp|http|https):\/\/docs\.google\.com\/viewer/;
		if( ! regexp.test( txtUrl ) ) {
			txtUrl = window.location.protocol + '//' + window.location.host + txtUrl;
			var srcEncoded = encodeURIComponent( txtUrl );
			iframe.setAttribute( 'src',     'https://docs.google.com/viewer?url=' + srcEncoded + '&embedded=true' );
		} else {
			var regexp = /(http):\/\/docs\.google\.com\/viewer/;
			if( regexp.test( txtUrl ) ) {
				txtUrl = txtUrl.replace("http://docs.google.com/viewer", "https://docs.google.com/viewer");
				iframe.setAttribute( 'src', txtUrl );
			} 
			else {
				iframe.setAttribute( 'src', txtUrl );
			}
		}
		
		iframe.setAttribute( 'width',   dialog.getValueOf( 'settingsTab', 'txtWidth' ) );
		iframe.setAttribute( 'height',  dialog.getValueOf( 'settingsTab', 'txtHeight' ) );
		iframe.setAttribute( 'style',   'border: none;' );
		editor.insertElement( iframe );
    }
  };
});

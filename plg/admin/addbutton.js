(function() {
    tinymce.create("tinymce.plugins.green_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //add new button    
            ed.addButton("green", {
                title : "Green Color Text",
                cmd : "green_command",
                image : "https://cdn3.iconfinder.com/data/icons/softwaredemo/PNG/32x32/Circle_Green.png"
            });
			
			
       
            //button functionality.
            ed.addCommand("green_command", function() {
                var getLink = function (editor, elm) {
				  return editor.dom.getParent(elm, 'span[data-v]');
				};
				var getSelectedLink = function (editor) {
				  return getLink(editor, editor.selection.getStart());
				};

				
				var sellink = getSelectedLink(ed);
				var seltext = "";
				var fecha = "";
				var data = "";
				
				
				if(sellink!=null){
					fecha=sellink.getAttribute('data-f');
					data=sellink.getAttribute('data-v').replace(/\[#\]/gi, '"');
					seltext = sellink.textContent.trim();
				}else{
					seltext = ed.selection.getContent({format: 'html'});
				}
                var wined;
                var subtiny;
                fun_ajax = function() {
                    c = document.querySelector('.mce-qs');
                    ed.setProgressState( 1 ); // Show progress
                    tinymce.util.XHR.send({
                        url: document.getElementById("query").value+"&id="+c.value,
                        success: function( res ) {
                            ed.setProgressState( 0 ); // Hide progress
                            document.querySelector('.mce-qpanel').innerHTML = res;
                            as = document.querySelectorAll('.mce-qpanel li');
                            as.forEach(function(el){
                                el.addEventListener("click", function(elemc,as){ 
                                    if(confirm("¿Está seguro que desea modificar el link?")){
                                        console.log(el.getAttribute('data-h'))
                                        tinymce.get('mitexto').execCommand('mceInsertContent', false, "<a href='"+el.getAttribute('data-h')+"'>"+el.textContent.trim()+"</a>");
                                    //wined.find("#href").value(el.getAttribute('data-h'))

                                    /*if(wined.find("#title").value().length<=0){
                                        wined.find("#title").value(el.textContent.trim())
                                    }
                                    if(wined.find("#data").value().length<=0){
                                        wined.find("#data").value(el.textContent.trim())
                                    }*/
                                }
                                    
                                    
                                 });
                            })
                        }
                    });
                };

				ed.selection.getStart()
				wined = ed.windowManager.open({
                    title: 'Burbuja',
                    body:[
                        {
                            type: 'container',
                            layout: 'grid',
                            columns: 2,
                            minWidth: 700,
                            minHeight: 400,
                            items:[
                                {
                                    type:'form',
                                    layout: 'flex',
                                    items:[
                                        {   
                                            type: 'listbox', label:'Categoría', name: 'cate','values': [
                                                {text:"Concordancia",value:"Concordancia"},
                                                {text:"Jurisprudencia",value:"Jurisprudencia"},
                                                {text:"Modificado",value:"Modificado"},
                                                {text:"Incorporado",value:"Incorporado"},
                                                {text:"Fe de erratas",value:"Fe de erratas"},
                                                {text:"Constitucional",value:"Constitucional"},
                                                {text:"Inconstitucional",value:"Inconstitucional"},
                                                {text:"Interpretado",value:"Interpretado"},
                                                {text:"Derogado",value:"Derogado"},
                                                {text:"(*)",value:"(*)"},
                                            ],
                                            value:seltext,
                                        },
                                        /*{
                                            type: 'textbox',
                                            label:'Nombre',
                                            name: 'title',
                                            autofocus: true,
                                            value:seltext,
                                            placeholder: 'Nombre',
                                            multiline: false,
                                            
                                        },*/
                                        {
                                            type: 'textbox',
                                            name: 'fecha',
                                            label: 'Fecha',
                                            value:fecha,
                                            multiline: false,
                                        },
                                        {
                                            type: 'panel',
                                            classes: 'pmitexto',
                                            items: [{
                                                type: 'textbox',
                                                name: 'data',
                                                value:data,
                                                classes: 'mitexto',
                                                multiline: true,
                                                minWidth: 500,
                                                minHeight: 150,
                                                id:'mitexto'
                                            }],
                                            multiline: true,
                                            minWidth: 500,
                                            minHeight: 220,
                                        }
                                    ],
                                
                                },
                                {
                                    type:'form',
                                    layout: 'flex',
                                    direction: 'column',
                                    items:[

                                            {
                                                type: 'label',
                                                text: 'Buscador',
                                                value:data,
                                                placeholder: 'Descripción',
                                                multiline: true,
                                                minWidth: 500,
                                            },
                                            {
                                                type: 'textbox',
                                                name: 'qs',
                                                classes: 'qs',
                                                placeholder: 'Buscar',
                                                multiline: false,
                                                onkeyup: fun_ajax
                                            },
                                            {
                                                type: 'panel',
                                                classes: 'qpanel',
                                                text: 'Buscar',
                                                value:data,
                                                placeholder: 'Descripción',
                                                multiline: true,
                                                minWidth: 500,
                                                minHeight: 300,
                                                
                                            }
                                        ]
                                },
                                
                            ]
                        }
                    ],
                   /* body: [
                        {
                            type:'panel',
                            column:2,
                            items:[
                                {   type: 'listbox', name: 'cate', label: 'Categoria', 'values': [
                                    {text:"Concordancia",value:"Concordancia"},
                                    {text:"Jurisprudencia",value:"Jurisprudencia"},
                                    {text:"Modificado",value:"Modificado"},
                                    {text:"Agregado",value:"Agregado"},
                                    {text:"Fe de erratas",value:"Fe de erratas"},
                                    {text:"Constitucional",value:"Constitucional"},
                                    {text:"Inconstitucional",value:"Inconstitucional"},
                                    {text:"Interpretado",value:"Interpretado"}]
                                },
                                {
                                    type: 'textbox',
                                    label:'Nombre',
                                    name: 'title',
                                    autofocus: true,
                                    value:seltext,
                                    placeholder: 'Nombre',
                                    multiline: false,
                                },
                                {
                                    type: 'textbox',
                                    name: 'href',
                                    label: 'URL',
                                    value:href,
                                    placeholder: 'URL',
                                    multiline: false,
                                },
                                {
                                    type: 'panel',
                                    classes: 'pmitexto',
                                    items: [{
                                        type: 'textbox',
                                        name: 'data',
                                        value:data,
                                        classes: 'mitexto',
                                        multiline: true,
                                        minWidth: 500,
                                        minHeight: 150,
                                    }],
                                    multiline: true,
                                    minWidth: 500,
                                    minHeight: 220,
                                },
                                {
                                    type: 'label',
                                    text: 'Buscador',
                                    value:data,
                                    placeholder: 'Descripción',
                                    multiline: true,
                                    minWidth: 500,
                                },
                                 {
                                    type: 'textbox',
                                    name: 'qs',
                                    classes: 'qs',
                                    placeholder: 'Buscar',
                                    multiline: false,
                                    onkeyup: fun_ajax
                                 },
                                 {
                                    type: 'panel',
                                    classes: 'qpanel',
                                    text: 'Buscar',
                                    value:data,
                                    placeholder: 'Descripción',
                                    multiline: true,
                                    minWidth: 500,
                                    minHeight: 200,
                                    
                                 }
                            ],
                            minWidth: 800,
                                        minHeight: 1050,
                        },
                    ],
                    */
                    onsubmit: function( e ) {
						if(sellink!=null){
                            sellink.remove();
							/*sellink.innerHTML = e.data.title;
							sellink.setAttribute('data-h',e.data.href);
							sellink.setAttribute('data-v',e.data.data.trim());
							sellink.insertBefore( document.createTextNode( " " ),sellink );*/
                        }
             
                            ed.insertContent( '<span class="relam" data-f="'+e.data.fecha+'" data-v="'+tinymce.get('mitexto').getContent().replace(/["']/gi, '[#]')+'">'+e.data.cate.trim()+'</span> ');
                            
						
                    },
                    onclose:function(){
                        tinymce.get('mitexto').execCommand('mceRemoveControl', true, 'mitexto');
                            tinymce.get('mitexto').remove();
                    }
                });

                fun_ajax();
                //console.log(tinymce)
              /*  padre  = document.querySelector('.mce-pmitexto .mce-container-body');
                document.querySelector('.mce-mitexto').setAttribute('style','width:490px; height:140px;');
                hijo = document.querySelector('.mce-mitexto');
                padre.appendChild(hijo);*/

                subtiny = tinymce.init({
                    selector: '#mitexto',
                    relative_urls : false,
                    height:220,
                    menubar: false,
                    plugins: 'lists link',
                    toolbar: ['bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify link code | bullist numlist outdent indent code']
                  });
            

				//appendInsertDialog();
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "Extra Buttons",
                author : "Narayan Prusty",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("green_button_plugin", tinymce.plugins.green_button_plugin);
})();
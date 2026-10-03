( function () {
	'use strict';

	var CFG = window.MIL_AI || {};
	var I18N = CFG.i18n || {};

	var form = document.getElementById( 'mil-ai-form' );
	if ( ! form ) {
		return;
	}

	var ideasBox = document.getElementById( 'mil-ai-ideas' );
	var writeBtn = document.getElementById( 'mil-ai-write' );
	var proposeBtn = document.getElementById( 'mil-ai-propose' );
	var selectAllBtn = document.getElementById( 'mil-ai-select-all' );
	var resultsSection = document.getElementById( 'mil-ai-results' );
	var articlesBox = document.getElementById( 'mil-ai-articles' );

	var spinnerPropose = document.getElementById( 'mil-ai-spinner-propose' );
	var spinnerWrite = document.getElementById( 'mil-ai-spinner-write' );

	var ideas = [];
	var pending = 0;

	function t( key, fallback ) {
		return I18N[ key ] || fallback || '';
	}

	function esc( value ) {
		return String( value === null || value === undefined ? '' : value )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' )
			.replace( /'/g, '&#039;' );
	}

	function serialize() {
		var params = {};
		var multiple = {};

		Array.prototype.forEach.call( form.querySelectorAll( '[name]' ), function ( el ) {
			var name = el.name;
			var value = el.value;

			if ( el.type === 'checkbox' ) {
				value = el.checked;
			}

			if ( el.multiple ) {
				multiple[ name ] = multiple[ name ] || [];
				Array.prototype.forEach.call( el.selectedOptions, function ( opt ) {
					multiple[ name ].push( opt.value );
				} );
				return;
			}

			params[ name ] = value;
		} );

		Object.keys( multiple ).forEach( function ( name ) {
			params[ name ] = multiple[ name ];
		} );

		return params;
	}

	function request( action, data, onDone ) {
		var body = new window.FormData();
		body.append( 'action', action );
		body.append( 'nonce', CFG.nonce );

		Object.keys( data ).forEach( function ( key ) {
			body.append( key, typeof data[ key ] === 'string' ? data[ key ] : JSON.stringify( data[ key ] ) );
		} );

		return window.fetch( CFG.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: body
		} )
			.then( function ( res ) {
				return res.json().catch( function () {
					throw new Error( 'HTTP ' + res.status );
				} );
			} )
			.then( function ( json ) {
				if ( ! json || true !== json.success ) {
					var msg = json && json.data && json.data.message ? json.data.message : 'Error desconocido.';
					throw new Error( msg );
				}
				if ( onDone ) {
					onDone( json.data );
				}
				return json.data;
			} );
	}

	function busy( button, spinner, label ) {
		button.disabled = true;
		var original = button.dataset.label || button.textContent;
		button.dataset.label = original;
		button.textContent = label;
		spinner.hidden = false;
	}

	function idle( button, spinner ) {
		button.disabled = false;
		button.textContent = button.dataset.label || button.textContent;
		spinner.hidden = true;
		syncWriteState();
	}

	function notice( box, message, kind ) {
		var el = document.createElement( 'div' );
		el.className = 'mil-ai__msg mil-ai__msg--' + ( kind || 'error' );
		el.textContent = message;
		box.appendChild( el );
		return el;
	}

	function clearNotices( box ) {
		Array.prototype.forEach.call( box.querySelectorAll( '.mil-ai__msg' ), function ( el ) {
			el.remove();
		} );
	}

	function selectedIdeas() {
		return ideas.filter( function ( idea ) {
			return idea.checked;
		} );
	}

	function syncWriteState() {
		var any = selectedIdeas().length > 0 && 0 === pending;
		writeBtn.disabled = ! any;
	}

	function renderIdeas() {
		ideasBox.innerHTML = '';

		if ( ! ideas.length ) {
			ideasBox.innerHTML = '<p class="mil-ai__empty">' + esc( t( 'vacio', 'Aun no hay propuestas.' ) ) + '</p>';
			syncWriteState();
			return;
		}

		ideas.forEach( function ( idea, index ) {
			var card = document.createElement( 'label' );
			card.className = 'mil-ai__idea' + ( idea.checked ? ' is-checked' : '' );

			var keywords = ( idea.keywords || [] ).slice( 0, 6 ).map( function ( k ) {
				return '<span class="mil-ai__tag">' + esc( k ) + '</span>';
			} ).join( '' );

			var outline = ( idea.esqueleto || [] ).map( function ( h ) {
				return esc( h );
			} ).join( ' &rsaquo; ' );

			card.innerHTML =
				'<input type="checkbox" ' + ( idea.checked ? 'checked' : '' ) + '>' +
				'<span class="mil-ai__idea-body">' +
					'<span class="mil-ai__idea-title">' + esc( idea.titulo ) + '</span>' +
					( idea.subtitulo ? '<span class="mil-ai__idea-meta">' + esc( idea.subtitulo ) + '</span>' : '' ) +
					( idea.angulo ? '<span class="mil-ai__idea-meta"><strong>Angulo:</strong> ' + esc( idea.angulo ) + '</span>' : '' ) +
					( idea.hook ? '<span class="mil-ai__idea-meta"><strong>Promesa:</strong> ' + esc( idea.hook ) + '</span>' : '' ) +
					( idea.razon ? '<span class="mil-ai__idea-meta">' + esc( idea.razon ) + '</span>' : '' ) +
					( outline ? '<span class="mil-ai__idea-meta"><strong>Esqueleto:</strong> ' + outline + '</span>' : '' ) +
					'<span class="mil-ai__tags">' +
						( idea.categoria ? '<span class="mil-ai__tag">' + esc( idea.categoria ) + '</span>' : '' ) +
						( idea.nivel ? '<span class="mil-ai__tag">' + esc( idea.nivel ) + '</span>' : '' ) +
						keywords +
					'</span>' +
				'</span>';

			var checkbox = card.querySelector( 'input' );
			checkbox.addEventListener( 'change', function () {
				idea.checked = checkbox.checked;
				card.classList.toggle( 'is-checked', idea.checked );
				syncWriteState();
			} );

			ideasBox.appendChild( card );
			idea.index = index;
		} );

		syncWriteState();
	}

	proposeBtn.addEventListener( 'click', function () {
		clearNotices( document.querySelector( '.mil-ai__panel--ideas' ) );

		var params = serialize();

		if ( ! String( params.tema || '' ).trim() ) {
			notice( document.querySelector( '.mil-ai__panel--ideas' ), t( 'vacio', 'Escribe un tema.' ), 'error' );
			return;
		}

		busy( proposeBtn, spinnerPropose, t( 'generando', 'Generando...' ) );

		request( 'mil_ai_propose', { params: params } )
			.then( function ( data ) {
				ideas = ( data.ideas || [] ).map( function ( idea, i ) {
					idea.checked = 0 === i;
					idea.index = i;
					return idea;
				} );
				renderIdeas();
			} )
			.catch( function ( err ) {
				notice( document.querySelector( '.mil-ai__panel--ideas' ), err.message, 'error' );
			} )
			.finally( function () {
				idle( proposeBtn, spinnerPropose );
			} );
	} );

	selectAllBtn.addEventListener( 'click', function () {
		var allChecked = selectedIdeas().length === ideas.length;
		ideas.forEach( function ( idea ) {
			idea.checked = ! allChecked;
		} );
		renderIdeas();
	} );

	writeBtn.addEventListener( 'click', function () {
		var queue = selectedIdeas().slice( 0, 10 );

		if ( ! queue.length ) {
			notice( document.querySelector( '.mil-ai__panel--ideas' ), t( 'selecciona', 'Selecciona al menos una propuesta.' ), 'error' );
			return;
		}

		var params = serialize();
		resultsSection.hidden = false;
		clearNotices( articlesBox );
		busy( writeBtn, spinnerWrite, t( 'generando', 'Generando...' ) );

		pending = queue.length;

		function next( index ) {
			if ( index >= queue.length ) {
				pending = 0;
				idle( writeBtn, spinnerWrite );
				resultsSection.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				return;
			}

			var slot = createSlot( queue[ index ] );

			request( 'mil_ai_write', { params: params, idea: queue[ index ] } )
				.then( function ( data ) {
					fillSlot( slot, data );
				} )
				.catch( function ( err ) {
					slot.body.innerHTML = '';
					notice( slot.body, err.message, 'error' );
				} )
				.finally( function () {
					next( index + 1 );
				} );
		}

		next( 0 );
	} );

	function createSlot( idea ) {
		var wrap = document.createElement( 'article' );
		wrap.className = 'mil-ai__article';

		wrap.innerHTML =
			'<div class="mil-ai__article-head">' +
				'<h3>' + esc( idea.titulo ) + '</h3>' +
				'<span class="mil-ai__badge">redactando</span>' +
			'</div>' +
			'<div class="mil-ai__article-body">' +
				'<div class="mil-ai__cover"><div class="mil-ai__cover-empty">creando imagen...</div></div>' +
				'<div class="mil-ai__fields"><p class="mil-ai__hint">Esperando al modelo...</p></div>' +
			'</div>';

		articlesBox.appendChild( wrap );

		return {
			root: wrap,
			head: wrap.querySelector( '.mil-ai__article-head' ),
			cover: wrap.querySelector( '.mil-ai__cover' ),
			body: wrap.querySelector( '.mil-ai__fields' )
		};
	}

	function fillSlot( slot, data ) {
		var article = data.article || {};
		var image = data.image || {};
		var idea = data.idea || {};

		slot.cover.innerHTML = image.url
			? '<img src="' + esc( image.url ) + '" alt="">'
			: '<div class="mil-ai__cover-empty">' + esc( data.image_error || t( 'sinImagen', 'Sin imagen.' ) ) + '</div>';

		slot.head.querySelector( '.mil-ai__badge' ).textContent =
			( article.palabras || 0 ) + ' palabras';

		var keywords = ( article.keywords || [] ).join( ', ' );

		var faq = '';
		if ( article.faq && article.faq.length ) {
			faq = '<label class="mil-ai__field"><span class="mil-ai__label">Preguntas frecuentes</span>' +
				'<ul class="mil-ai__faq">' +
				article.faq.map( function ( item ) {
					return '<li><strong>' + esc( item.pregunta ) + '</strong> ' + esc( item.respuesta ) + '</li>';
				} ).join( '' ) +
				'</ul></label>';
		}

		var payloadId = 'mil-ai-payload-' + Math.random().toString( 36 ).slice( 2, 10 );

		slot.body.innerHTML =
			'<label class="mil-ai__field"><span class="mil-ai__label">Titulo</span>' +
				'<input type="text" data-role="titulo" value="' + esc( article.titulo ) + '"></label>' +
			'<label class="mil-ai__field"><span class="mil-ai__label">Slug</span>' +
				'<input type="text" data-role="slug" value="' + esc( article.slug ) + '"></label>' +
			'<label class="mil-ai__field"><span class="mil-ai__label">Sumilla / meta description</span>' +
				'<textarea data-role="sumilla" rows="2">' + esc( article.sumilla ) + '</textarea></label>' +
			'<label class="mil-ai__field"><span class="mil-ai__label">Meta description (SEO)</span>' +
				'<input type="text" data-role="meta_description" value="' + esc( article.meta_description ) + '"></label>' +
			'<label class="mil-ai__field"><span class="mil-ai__label">Etiquetas</span>' +
				'<input type="text" data-role="keywords" value="' + esc( keywords ) + '"></label>' +
			'<label class="mil-ai__field"><span class="mil-ai__label">Contenido HTML</span>' +
				'<textarea data-role="contenido_html" rows="16">' + esc( article.contenido_html ) + '</textarea></label>' +
			'<div class="mil-ai__actions">' +
				'<button type="button" class="button mil-ai__btn" data-role="preview-btn">Ver vista previa</button>' +
				'<button type="button" class="button button-primary mil-ai__btn" data-role="save-btn">Guardar en WordPress</button>' +
				'<span class="mil-ai__spinner" data-role="save-spinner" hidden></span>' +
			'</div>' +
			'<div class="mil-ai__preview" data-role="preview"></div>' +
			( faq ? faq : '' ) +
			( article.notas_verificacion
				? '<div class="mil-ai__notes"><strong>Verificar antes de publicar:</strong> ' + esc( article.notas_verificacion ) + '</div>'
				: '' );

		var store = {
			idea: idea,
			article: article,
			image: image
		};

		slot.body.querySelector( '[data-role="preview-btn"]' ).addEventListener( 'click', function ( ev ) {
			var preview = slot.body.querySelector( '[data-role="preview"]' );
			var active = ev.target.dataset.active === '1';
			preview.innerHTML = active ? '' : slot.body.querySelector( '[data-role="contenido_html"]' ).value;
			ev.target.dataset.active = active ? '0' : '1';
			ev.target.textContent = active ? 'Ver vista previa' : 'Ocultar vista previa';
		} );

		var saveBtn = slot.body.querySelector( '[data-role="save-btn"]' );
		var saveSpinner = slot.body.querySelector( '[data-role="save-spinner]' );
		var saveLabel = saveBtn.textContent;

		saveBtn.addEventListener( 'click', function () {
			store.article.titulo = slot.body.querySelector( '[data-role="titulo"]' ).value;
			store.article.slug = slot.body.querySelector( '[data-role="slug"]' ).value;
			store.article.sumilla = slot.body.querySelector( '[data-role="sumilla"]' ).value;
			store.article.meta_description = slot.body.querySelector( '[data-role="meta_description"]' ).value;
			store.article.keywords = slot.body.querySelector( '[data-role="keywords"]' ).value
				.split( ',' )
				.map( function ( k ) {
					return k.trim();
				} )
				.filter( Boolean );
			store.article.contenido_html = slot.body.querySelector( '[data-role="contenido_html"]' ).value;

			saveBtn.disabled = true;
			saveBtn.textContent = t( 'guardando', 'Guardando...' );
			saveSpinner.hidden = false;

			request( 'mil_ai_save', { params: serialize(), payload: store } )
				.then( function ( data ) {
					var done = notice(
						slot.body,
						'draft' === data.status ? 'Borrador creado.' : 'Publicado.',
						'ok'
					);

					var links = document.createElement( 'span' );
					links.className = 'mil-ai__actions';
					links.innerHTML =
						'<a class="button button-primary" href="' + esc( data.editUrl ) + '">Editar en WordPress</a>' +
						'<a class="button" href="' + esc( data.viewUrl ) + '" target="_blank" rel="noopener">Ver en el sitio</a>';
					done.appendChild( links );

					saveBtn.textContent = 'Guardado';
				} )
				.catch( function ( err ) {
					notice( slot.body, err.message, 'error' );
					saveBtn.disabled = false;
					saveBtn.textContent = saveLabel;
				} )
				.finally( function () {
					saveSpinner.hidden = true;
					writeBtn.disabled = false;
					syncWriteState();
				} );
		} );

		slot.root.setAttribute( 'data-payload-id', payloadId );
	}
} )();

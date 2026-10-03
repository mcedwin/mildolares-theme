( function () {
	'use strict';

	var CFG = window.MIL_AI || {};
	var I18N = CFG.i18n || {};

	var form = document.getElementById( 'mil-ai-form' );
	if ( ! form ) {
		return;
	}

	var ideasBox = document.getElementById( 'mil-ai-ideas' );
	var proposeBtn = document.getElementById( 'mil-ai-propose' );
	var approveBtn = document.getElementById( 'mil-ai-approve' );
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

	function request( action, data ) {
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
				return json.data;
			} );
	}

	function busy( button, spinner, label ) {
		button.disabled = true;
		button.dataset.label = button.dataset.label || button.textContent;
		button.textContent = label;
		if ( spinner ) {
			spinner.hidden = false;
		}
	}

	function idle( button, spinner ) {
		if ( spinner ) {
			spinner.hidden = true;
		}
		if ( button.dataset.label ) {
			button.textContent = button.dataset.label;
		}
		syncState();
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

	function syncState() {
		var any = selectedIdeas().length > 0 && 0 === pending;

		if ( approveBtn ) {
			approveBtn.disabled = ! any;
		}
	}

	function renderIdeas() {
		ideasBox.innerHTML = '';

		if ( ! ideas.length ) {
			ideasBox.innerHTML = '<p class="mil-ai__empty">' + esc( t( 'vacio', 'Aun no hay propuestas.' ) ) + '</p>';
			syncState();
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
						( idea.categoria ? '<span class="mil-ai__tag' + ( idea.nueva ? ' mil-ai__tag--new' : '' ) + '">' + esc( idea.categoria ) + ( idea.nueva ? ' · nueva' : '' ) + '</span>' : '' ) +
						( idea.nivel ? '<span class="mil-ai__tag">' + esc( idea.nivel ) + '</span>' : '' ) +
						keywords +
					'</span>' +
				'</span>';

			var checkbox = card.querySelector( 'input' );
			checkbox.addEventListener( 'change', function () {
				idea.checked = checkbox.checked;
				card.classList.toggle( 'is-checked', idea.checked );
				syncState();
			} );

			ideasBox.appendChild( card );
			idea.index = index;
		} );

		syncState();
	}

	proposeBtn.addEventListener( 'click', function () {
		var panel = document.querySelector( '.mil-ai__panel--ideas' );
		clearNotices( panel );

		var params = serialize();

		if ( ! String( params.tema || '' ).trim() ) {
			notice( panel, t( 'vacio', 'Escribe un tema.' ), 'error' );
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
				notice( panel, err.message, 'error' );
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

	if ( approveBtn ) {
		approveBtn.addEventListener( 'click', function () {
			var queue = selectedIdeas().slice( 0, 12 );

			if ( ! queue.length ) {
				notice( document.querySelector( '.mil-ai__panel--ideas' ), t( 'selecciona', 'Selecciona al menos una propuesta.' ), 'error' );
				return;
			}

			var params = serialize();

			resultsSection.hidden = false;
			articlesBox.innerHTML = '';
			busy( approveBtn, spinnerWrite, 'Aprobando y publicando...' );
			pending = queue.length;

			request( 'mil_ai_approve', { params: params, ideas: queue } )
				.then( function ( data ) {
					var created = data.created || [];

					notice( articlesBox, 'Articulos creados y publicados: ' + created.length, 'ok' );

					if ( created.length ) {
						var list = document.createElement( 'ul' );
						list.className = 'mil-ai__faq';

						created.forEach( function ( c ) {
							var li = document.createElement( 'li' );
							var a1 = document.createElement( 'a' );
							a1.href = c.editUrl;
							a1.textContent = c.titulo;
							var a2 = document.createElement( 'a' );
							a2.href = c.viewUrl;
							a2.target = '_blank';
							a2.rel = 'noopener';
							a2.textContent = 'Ver';
							li.appendChild( a1 );
							li.appendChild( document.createTextNode( ' - ' ) );
							li.appendChild( a2 );
							list.appendChild( li );
						} );

						articlesBox.appendChild( list );
					}

					ideas = ideas.filter( function ( idea ) {
						return ! idea.checked;
					} );
					renderIdeas();

					resultsSection.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				} )
				.catch( function ( err ) {
					notice( articlesBox, err.message, 'error' );
				} )
				.finally( function () {
					pending = 0;
					idle( approveBtn, spinnerWrite );
				} );
		} );
	}
	var autofillBtn = document.getElementById( 'mil-ai-autofill' );

	if ( autofillBtn ) {
		var autofillLimit = document.getElementById( 'mil-ai-images-limit' );
		var autofillSpinner = document.getElementById( 'mil-ai-spinner-images' );
		var autofillBox = document.getElementById( 'mil-ai-images-results' );

		autofillBtn.addEventListener( 'click', function () {
			var limit = autofillLimit ? parseInt( autofillLimit.value, 10 ) : 10;

			if ( ! limit || limit < 1 ) {
				limit = 10;
			}

			clearNotices( autofillBox );
			autofillBox.innerHTML = '';
			busy( autofillBtn, autofillSpinner, 'Buscando imagenes...' );

			request( 'mil_ai_autofill', { limit: String( limit ) } )
				.then( function ( data ) {
					var results = data.results || [];
					var ok = 0;

					results.forEach( function ( r ) {
						if ( r.ok ) {
							ok++;
						}
					} );

					notice(
						autofillBox,
						'Procesados ' + results.length + ' · asignadas ' + ok + ' · quedan ' + ( data.remaining || 0 ) + ' sin foto',
						ok > 0 ? 'ok' : 'error'
					);

					if ( results.length ) {
						var list = document.createElement( 'ul' );
						list.className = 'mil-ai__faq';

						results.forEach( function ( r ) {
							var li = document.createElement( 'li' );
							li.className = 'mil-ai__imgrow' + ( r.ok ? ' is-ok' : ' is-err' );

							if ( r.ok && r.url ) {
								var img = document.createElement( 'img' );
								img.src = r.url;
								img.alt = '';
								img.loading = 'lazy';
								li.appendChild( img );
							}

							var link = document.createElement( 'a' );
							link.href = r.editUrl || '#';
							link.textContent = r.titulo + ( r.categoria ? ' [' + r.categoria + ']' : '' );
							li.appendChild( link );

							var status = document.createElement( 'span' );
							status.textContent = r.ok ? ' ok' : ' ' + ( r.error || 'error' );
							li.appendChild( status );

							list.appendChild( li );
						} );

						autofillBox.appendChild( list );
					}
				} )
				.catch( function ( err ) {
					notice( autofillBox, err.message, 'error' );
				} )
				.finally( function () {
					idle( autofillBtn, autofillSpinner );
				} );
		} );
	}
} )();

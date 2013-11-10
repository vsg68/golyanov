
$(window).load(function(){

	$('img', '.theme').opacityrollover({mouseOutOpacity:   0.3});

 	$('.accordion-opener').hover( function () {
			$(this).parent().toggleClass('shadow-text');
	})

	$('.tip').poshytip({
		    	  className: 'tip-darkgray',
		    	  liveEvents: true,
				  alignX: 'center',
				  offsetY: 20,
				  showTimeout:	300,
				  timeOnScreen: 1000,
				  fade: false,
				  slide: false
	});

    $('a[rel]').live('click', show_gallery );

	/*
	 * Вывод репортажей в архиве
	 */
	$('a','.archive-panel').live('click', function(e){

		e.preventDefault();
		var id = $(this).attr('rel');

		/* Если повторно кликаем по картинке - выходим */
		if( $(this).hasClass('selected-report') )
		{
			return false;
		}
		$('#reports-place').slideUp(100);

		$.ajax({
			url: '/index-ajax.php',
			type: 'post',
			data: {
				q: 'assets/snippets/ajax/getContent.php',
				id: id
			},
  		    success: function(response) {

				$('.selected-report').removeClass('selected-report');

				$("div#reports-place")
						.empty()
						.html(response);
				$('a[rel='+id+']').addClass('selected-report');
				$('#reports-place').slideDown('normal');
				return false;
			}
		});


	});
});


function show_gallery() {


		var id = $(this).attr('rel');

		/* Предотвращаем вовторный клик до загрузки */

		if( $('.clicked-now').size() )
		{
			return false;
		}

		$('img','a[rel='+id+']').addClass('selected');

		/* Для репортажей отдельная картинка лоадера */
		if( $(this).parent().hasClass('newsblock') )
		{
			$(this).after( "<div class='loader-img-small'></div>" );
		}
		else
		{
			$(this).after( "<div class='loader-img'></div>" );
		}



		/* Закрываем, когда вызов идет от темы */
		if( $(this).parent().hasClass('theme') )
		{
			$('#series-place').slideUp('normal');
		}

		$.ajax({
				url: '/index-ajax.php',
				type: 'post',
				data: {
						q: 'assets/snippets/ajax/Content.php',
						id: id,
						},
				error: function () {
							isError(id);
						},
				success: function(response) {

								if( ! response )
									return isError(id);

								beforeShow(id);

								data = $(response).html();

								if( $(data).hasClass('highslide') )	{
									/* выбираем все ID для последующей инициализации */
									var yaId = {};
									$(data).find('.share').each( function(){

										//yaId.push( $(this).attr('id') :);
										yaId[ $(this).attr('id') ] = $(this).parent().text();
									});

									$("div#gallery-place")
										.empty()
										.html(data);

									/* инициализируем yandex-share */
									$('.b-share-popup-wrap').remove();

									for( var key in yaId)	{
										Social(key, yaId[key]);
									}

									$('a:first.highslide').click();
								}
								else if( $(data).hasClass('series') ) {
									$('#series-panel')
										.empty()
										.html(data);

									$('img','a[rel='+id+']').addClass('selected');
									$('img', '.series').opacityrollover({mouseOutOpacity: 0.3});

									$('#series-place').slideDown('normal');
								}
						}
					});
}

function submit_form() {
    // disable the submit button
    $('#submitbutton').attr("disabled", true);

	$('#mailform').load('/index.php', {
				id:		  23,
				email:    $("input[name='email']").val(),
				name:     $("input[name='name']").val(),
				comments: $("textarea[name='comments']").val(),
				formid:   $("input[name='formid']").val(),
				vericode: $("input[name='vericode']").val()
			});

 }

function Social(id, text) {



    new Ya.share({
        element: id,
		l10n: 'ru',
		link:  window.location.protocol+'//'+window.location.host +'/img/?fid=' + id.replace('ya-',''),
		title: text,
        elementStyle: {
                type: 'icon',
				quickServices: [],
        },
        popupStyle: {
            blocks: {
                    'Поделитесь с друзьями!': ['yaru', 'twitter', 'vkontakte', 'facebook', 'lj', 'moikrug', 'moimir', 'odnoklassniki']
            },
			copyPasteField: true
        }
	});
};

function getThumbs(gid, page) {

	var page = page + 1;
	var data = '';
	var id = gid;
	$.ajax({
			url: '/index-ajax.php',
			type: 'post',
			data: {
					q: 'assets/snippets/ajax/Content.php',
					id: id,
					page: page
					},
			error: function () {
						isError(id);
					},
  		    success: function(response) {
						data = response;
					}
	});
	x = data;
	return data;
}

function isError(id) {
		$('.clicked-now').removeClass('.clicked-now');
		// нужно какое-то сообщение
		alert('Что-то пошло не так... (С)');

		beforeShow(id);
}

function beforeShow(id) {

		$('.loader-img-small').removeClass('loader-img-small');
		$('.loader-img').removeClass('loader-img');
		$('img','a[rel='+id+']').removeClass('selected');
}

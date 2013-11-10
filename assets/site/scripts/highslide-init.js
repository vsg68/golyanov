
	hs.graphicsDir = '/assets/libs/viewers/highslide/graphics/';
	hs.transitions = ['expand', 'crossfade'];
	hs.fadeInOut = true;
	hs.showCredits = false;
	hs.align = 'center';
	hs.outlineType = 'rounded-white';
	hs.numberOfImagesToPreload = 5;
	hs.onDimmerClick = function() {  return false;  }; /* убирает выход по клику на бекграунде */
hs.numberPosition = 'caption'; 
hs.dragByHeading = false;
	hs.lang.number = 'Фото: %1/%2';
	hs.lang.loadingText = '';
	hs.lang.previousText = 'Предыдущий';
	hs.lang.nextText = 'Следующий';
	hs.lang.moveText = 'Переместить';
	hs.lang.closeText = 'Закрыть'; 
	hs.lang.closeTitle = 'Закрыть (esc)';
	hs.lang.resizeTitle = 'Изменить размер';
	hs.lang.playText = 'Запуск';
	hs.lang.playTitle = 'Запуск слайдшоу (spacebar)';
	hs.lang.pauseText = 'Пауза';
	hs.lang.pauseTitle = 'Пауза слайдшоу (spacebar)';
	hs.lang.previousTitle = 'Сюда';
	hs.lang.nextTitle = 'Туда';
	hs.lang.moveTitle = 'Переместить';
	hs.lang.restoreTitle = 'Нажмите, чтоб закрыть фото, нажмите левую клавишу мышки, чтобы переместить фото. Используйте стрелки для навигации.';

	// Add the slideshow providing the controlbar and the thumbstrip
	hs.addSlideshow({
		slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: true,
		fixedControls: false,
		overlayOptions: {
			/* position: 'top right', */
			position: 'bottom center',
			opacity: 0.6,
			hideOnMouseOut: true
		},
		thumbstrip: {
			position: 'middle left',
			mode: 'vertical',
			relativeTo: 'viewport'
		}
	});
	
	
	var GalleryOptions = {
			slideshowGroup: 'group1',
			dimmingOpacity: 0.9,
			/* wrapperClassName: 'controls-in-heading', */
			wrapperClassName: 'white borderless floating-caption',
			allowSizeReduction: true,
			headingEval: 'this.thumb.alt'
	};	
	
	var MailOptions = {
			objectType: 'ajax', 
			headingText: 'Для писем и газет',
			numberPosition: null, 
			dimmingOpacity: 0.1, 
			useControls: true,
			cacheAjax: false,
			wrapperClassName: 'draggable-header titlebar' 
	};

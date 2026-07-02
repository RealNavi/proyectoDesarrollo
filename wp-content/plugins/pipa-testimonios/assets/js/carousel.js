document.addEventListener( 'DOMContentLoaded', function () {
	var carousels = document.querySelectorAll( '[data-pipa-carousel]' );

	carousels.forEach( function ( carousel ) {
		var track = carousel.querySelector( '.pipa-testimonios-track' );
		var slides = Array.prototype.slice.call( carousel.querySelectorAll( '.pipa-testimonio-slide' ) );
		var prevBtn = carousel.querySelector( '.pipa-carousel-arrow--prev' );
		var nextBtn = carousel.querySelector( '.pipa-carousel-arrow--next' );
		var dotsWrap = carousel.querySelector( '.pipa-carousel-dots' );
		var current = 0;

		if ( slides.length < 2 ) {
			if ( prevBtn ) prevBtn.style.display = 'none';
			if ( nextBtn ) nextBtn.style.display = 'none';
			return;
		}

		slides.forEach( function ( slide, index ) {
			var dot = document.createElement( 'button' );
			dot.type = 'button';
			dot.className = 'pipa-carousel-dot' + ( index === 0 ? ' is-active' : '' );
			dot.setAttribute( 'aria-label', 'Ir al testimonio ' + ( index + 1 ) );
			dot.addEventListener( 'click', function () {
				goTo( index );
			} );
			dotsWrap.appendChild( dot );
		} );

		var dots = Array.prototype.slice.call( dotsWrap.querySelectorAll( '.pipa-carousel-dot' ) );

		function goTo( index ) {
			current = ( index + slides.length ) % slides.length;
			track.style.transform = 'translateX(-' + ( current * 100 ) + '%)';
			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === current );
			} );
		}

		prevBtn.addEventListener( 'click', function () {
			goTo( current - 1 );
		} );
		nextBtn.addEventListener( 'click', function () {
			goTo( current + 1 );
		} );
	} );
} );

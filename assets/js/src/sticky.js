var nav = document.querySelector('.custom-logo-link');

window.addEventListener('scroll', () => {
	if(window.innerWidth > 600) {
		if(window.pageYOffset > 50) {
			nav.classList.add('small');
		} else {
			nav.classList.remove('small');
		}
	}
});

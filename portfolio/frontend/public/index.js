window.onload = function () {
    window.onscroll = function () {
        if (this.scrollY > 20) {
            this.document.querySelector('#header').classList.add('sticky');
        } else {
            this.document.querySelector('#header').classList.remove('sticky');
        }
    };

    // showing options menu in the nabvar with litle screens
    document.querySelector('.menu-btn').addEventListener('click', function () {
        document.querySelector('.menu').classList.toggle('active');
        document.querySelector('i').classList.toggle('active');
    });
}
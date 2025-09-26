document.addEventListener('DOMContentLoaded', function () {
    // テキストフェードイン
    const textElement = document.querySelector('.delayed-text');
    setTimeout(() => {
        textElement.style.opacity = 1;
    }, 1000);

    // 花びらのアニメーションを追加
    const numPetals = 20;
    const petalContainer = document.getElementById('falling-petals');
    const petalImages = [
        '{{ asset('storage/images/petals/petal1.png') }}',
        '{{ asset('storage/images/petals/petal2.png') }}',
        '{{ asset('storage/images/petals/petal3.png') }}',
        '{{ asset('storage/images/petals/petal4.png') }}'
    ];

    function createPetal() {
        const petal = document.createElement('div');
        petal.className = 'petal';
        petal.style.left = Math.random() * 100 + 'vw';
        petal.style.animationDuration = (Math.random() * 3 + 2) + 's';
        petal.style.animationDelay = Math.random() * 5 + 's';
        petal.style.backgroundImage = 'url(' + petalImages[Math.floor(Math.random() * petalImages.length)] + ')';
        petalContainer.appendChild(petal);

        // 花びらが消えたら削除
        petal.addEventListener('animationend', () => {
            petal.remove();
            createPetal();
        });
    }

    for (let i = 0; i < numPetals; i++) {
        createPetal();
    }
});

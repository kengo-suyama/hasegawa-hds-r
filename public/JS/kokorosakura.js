$(document).ready(function() {
    // ゆっくり舞い散る桜エフェクト
    $('.falling-petals').sakura({
        className:    'sakura',  // 花びらに付与するクラス名
        fallSpeed:    0.5,       // 落下速度（小さいほどゆっくり）
        maxSize:      25,        // 花びらの最大サイズ(px)
        minSize:      10,        // 花びらの最小サイズ(px)
        newOn:        800        // 生成間隔（ミリ秒）
    });
});

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>心桜 認知症診断(改良版)</title>
  <!-- Bootstrap & Font Awesome -->
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <!-- CSS for 桜エフェクト(読み込む場合は適宜設定) -->
  <link rel="stylesheet" href="{{ asset('css/jquery-sakura.min.css') }}" />

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Satisfy&display=swap');

    body {
      background-color: #ffd1dc;
      font-size: 1.2rem;
      overflow-x: hidden;
      /* 横スクロールを抑制 */
    }

    .title-section h2 {
      font-family: 'Satisfy', cursive;
      color: #d63384;
      text-align: center;
      margin-top: 2rem;
      margin-bottom: 1rem;
      font-size: 3rem;
    }

    .instructions {
      margin: 0 auto;
      max-width: 600px;
    }

    .btn-start {
      display: block;
      margin: 1rem auto;
      font-size: 1.5rem;
      font-weight: bold;
      padding: 0.7rem 1.5rem;
    }

    /* モーダルのデザイン */
    .modal-content {
      border-radius: 10px;
    }

    .modal-header {
      background-color: #ffe1e9;
      border-bottom: none;
      border-radius: 10px 10px 0 0;
    }

    .modal-body {
      background-color: #ffffff;
    }

    .modal-footer {
      background-color: #ffe1e9;
      border-top: none;
      border-radius: 0 0 10px 10px;
    }

    .question {
      font-weight: bold;
      margin-bottom: 1.5rem;
    }

    .advice {
      font-size: 0.9rem;
      margin-top: 1rem;
    }

    .btn-next,
    .btn-prev {
      margin-top: 1rem;
    }

    /* ワンポイントアドバイス用 */
    .extra-info {
      background-color: #fffbee;
      border: 1px solid #ffe9c4;
      border-radius: 5px;
      padding: 10px;
      margin-top: 15px;
    }

    .extra-info h6 {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .extra-info p {
      margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
      .title-section h2 {
        font-size: 2rem;
      }

      .btn-start {
        font-size: 1.2rem;
      }
    }
  </style>
</head>

<body>
  <div class="title-section">
    <h2>心桜 認知症診断</h2>
  </div>

  <!-- ================================
       イントロ画面（生年月日入力）
       ================================ -->
  <div class="container instructions" id="introSection">
    <h5>検査の導入にあたっての注意</h5>
    <p>
      検査を開始する際は、「最近、物忘れでお困りではないですか？」などと
      やさしく尋ねることをお勧めします。<br />
      患者様がリラックスして検査に臨めるよう、心を配ってください。
    </p>
    <h5>検査を終了した後の注意点</h5>
    <p>
      検査が終わったら、「お疲れ様でした。どうですか、疲れましたか？」と
      声をかけ、患者様が安心できるように努めてください。
    </p>

    <!-- 生年月日入力フォーム -->
    <div class="form-inline mb-3">
      <label for="birthEra" class="mr-2">元号:</label>
      <select class="form-control mr-2" id="birthEra">
        <option value="showa">昭和</option>
        <option value="heisei">平成</option>
        <option value="reiwa">令和</option>
      </select>
      <label for="birthYear" class="mr-2">年:</label>
      <input
        type="number"
        class="form-control mr-2"
        id="birthYear"
        placeholder="例: 50"
        style="width: 80px;"
        min="1"
        max="99" />
      <label for="birthMonth" class="mr-2">月:</label>
      <input
        type="number"
        class="form-control mr-2"
        id="birthMonth"
        placeholder="例: 5"
        style="width: 80px;"
        min="1"
        max="12" />
      <label for="birthDay" class="mr-2">日:</label>
      <input
        type="number"
        class="form-control"
        id="birthDay"
        placeholder="例: 20"
        style="width: 80px;"
        min="1"
        max="31" />
    </div>
    <button class="btn btn-primary" onclick="calculateAge()">年齢を計算</button>

    <div class="mt-3" id="calculatedAgeArea" style="display: none;">
      <p>推定年齢: <span id="calculatedAgeSpan" style="font-weight:bold;"></span> 歳</p>
      <p style="font-size:0.9rem;color:#555;">
        ※保険証などで確認した年齢と一致するかご確認ください
      </p>
    </div>

    <button
      class="btn btn-success btn-start"
      onclick="startQuiz()"
      disabled
      id="startQuizBtn">
      検査を開始する
    </button>
  </div>

  <!-- ========== Modal 1 (設問1: 2択) ========== -->
  <div class="modal fade" id="modalQuestion1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問1: 年齢を答えてください。</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- 計算した年齢をここに表示 -->
          <div class="question">
            <p>
              先ほど計算した年齢は
              <span id="calculatedAgeDisplay" style="font-weight:bold;"></span>
              歳ですね。<br />
              受検者が答えた年齢がこの年齢 ±2 以内なら「正解」ボタン、<br />
              それ以外なら「不正解」ボタンを押してください。
            </p>
          </div>
          <!-- 2ボタンのみ -->
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 2)">
            正解（±2年以内）
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 2)">
            不正解（±2年外）
          </button>
          <div class="advice alert alert-secondary mt-3">
            検査者は「正しい年齢」を把握しておき、±2年かどうかを判断してください。
          </div>

          <!-- ワンポイントアドバイス（設問1） -->
          <div class="extra-info mt-3">
            <h6>【設問1のポイント：年齢】</h6>
            <p>
              <strong>なぜ ±2年を正解とする？</strong><br />
              数え年で答える方、誕生日を迎えているかどうかなど、年齢に誤差が生じる可能性があるためです。
            </p>
            <p>
              <strong>生年月日を言えたのに年齢が答えられない場合は？</strong><br />
              その場合は0点になります。年齢という数値を理解しているかどうかが重要です。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <!-- 設問1は"戻る"ボタン不要なので disabled -->
          <button type="button" class="btn btn-secondary btn-prev" disabled>
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 2 (設問2) ========== -->
  <div
    class="modal fade"
    id="modalQuestion2"
    tabindex="-1"
    aria-labelledby="modalQuestion2Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalQuestion2Label">設問2</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            今日は何年の何月何日ですか？ 何曜日ですか？
          </div>
          <!-- 年・月・日・曜日の正誤選択 -->
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="yearSelect">年</label>
              <select class="form-control" id="yearSelect">
                <option value="0">不正解</option>
                <option value="1">正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="monthSelect">月</label>
              <select class="form-control" id="monthSelect">
                <option value="0">不正解</option>
                <option value="1">正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="daySelect">日</label>
              <select class="form-control" id="daySelect">
                <option value="0">不正解</option>
                <option value="1">正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="weekdaySelect">曜日</label>
              <select class="form-control" id="weekdaySelect">
                <option value="0">不正解</option>
                <option value="1">正解</option>
              </select>
            </div>
          </div>

          <div class="advice alert alert-secondary mt-3">
            時間の見当識を問う設問です。逆順で聞いてもOKです。
          </div>

          <!-- ワンポイントアドバイス (設問2) -->
          <div class="extra-info">
            <h6>【設問2のポイント：今日の日付・曜日】</h6>
            <p>
              <strong>順番は固定？</strong><br />
              特に固定ではありません。「何月何日？」「今日は何曜日？」と逆から聞く方が答えやすい場合もあります。
            </p>
            <p>
              <strong>何が大切？</strong><br />
              日付を正しく理解できているかを確認することが目的です。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <!-- 戻る先はModal1 -->
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(1)">
            戻る
          </button>
          <button
            type="button"
            class="btn btn-primary btn-next"
            onclick="submitQuestion2()">
            次へ
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 3 ========== -->
  <div
    class="modal fade"
    id="modalQuestion3"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問3</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            私たちが今いるところはどこですか？
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(2, 4)">
            自発的に正解 (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 4)">
            ヒント後に正解 (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 4)">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            病院名を言えなくても、「病院・施設にいる」と理解していれば正解です。
          </div>

          <!-- ワンポイントアドバイス（設問3） -->
          <div class="extra-info">
            <h6>【設問3のポイント：場所の見当識】</h6>
            <p>
              <strong>病院名を答えられなくてもいい？</strong><br />
              はい。「病院」「施設」「家」など本質的にどこかを理解していればOKです。
            </p>
            <p>
              <strong>ヒントはどうする？</strong><br />
              「家ですか？」「施設ですか？」など、複数の選択肢を挙げて導いてもかまいません。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(2)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 4 ========== -->
  <div
    class="modal fade"
    id="modalQuestion4"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問4</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            これから言う3つの言葉を言ってみてください。（例：'桜', '猫', '電車'）
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(3, 5)">
            全て言えた (3点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(2, 5)">
            2つ言えた (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 5)">
            1つ言えた (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 5)">
            言えない (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            言えたあとで何度か繰り返し、3つ全て覚えてもらいましょう（最大3回）。
          </div>

          <!-- ワンポイントアドバイス（設問4） -->
          <div class="extra-info">
            <h6>【設問4のポイント：3つの言葉の記銘】</h6>
            <p>
              <strong>なぜ桜・猫・電車なの？</strong><br />
              「植物・動物・乗り物」で連想しやすいが、互いに関係がない3種類を選んでいます。
            </p>
            <p>
              <strong>2つしか覚えられない場合？</strong><br />
              2点で採点しますが、覚え直してもらうよう最大3回まで繰り返してOKです。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(3)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 5 ========== -->
  <div
    class="modal fade"
    id="modalQuestion5"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問5</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            100から7を順番に引いてください。(例: 93, 86, 79...)
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 6)">
            93と正答 (1点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(2, 6)">
            93、86と連続正答 (2点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 6)">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            「93」を答えられたら「そこからさらに7を引くと？」と質問。
          </div>

          <!-- ワンポイントアドバイス（設問5） -->
          <div class="extra-info">
            <h6>【設問5のポイント：引き算】</h6>
            <p>
              <strong>「93引く7は？」と聞いてもいい？</strong><br />
              ダメです。検査者から「93」という数字を口にすると、作業記憶ではなくただの引き算になってしまいます。
            </p>
            <p>
              <strong>最初に間違えたら？</strong><br />
              そこで打ち切り、次の設問へ進みます。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(4)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 6 ========== -->
  <div
    class="modal fade"
    id="modalQuestion6"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問6</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            これから言う数字を逆から言ってください。(例: 「6-8-2」→「2-8-6」)
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 7)">
            全て正解 (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 7)">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            3桁で失敗したら打ち切り。1秒間隔でゆっくり読み上げると良いです。
          </div>

          <!-- ワンポイントアドバイス（設問6） -->
          <div class="extra-info">
            <h6>【設問6のポイント：数字の逆唱】</h6>
            <p>
              <strong>どのくらいの速さ？</strong><br />
              1秒に1数字くらい。早口だと混乱しやすいので注意しましょう。
            </p>
            <p>
              <strong>4桁以上は？</strong><br />
              3桁で失敗した時点で終了します。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(5)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 7 ========== -->
  <div
    class="modal fade"
    id="modalQuestion7"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問7</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            先ほど覚えてもらった言葉をもう一度言ってみてください。
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(3, 8)">
            全て正解 (3点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(2, 8)">
            部分的正解 (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            onclick="handleAnswer(1, 8)">
            ヒント後に正解 (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            onclick="handleAnswer(0, 8)">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            ヒントを与える際は、1つずつ区切って出してください。
          </div>
          <!-- ワンポイントアドバイス（設問7） -->
          <div class="extra-info">
            <h6>【設問7のポイント：3つの言葉の遅延再生】</h6>
            <p>
              <strong>ヒントをまとめて与えていい？</strong><br />
              いいえ、一気に「動物と乗り物が…」と言うより、1つずつ区切った方が望ましいです。
            </p>
            <p>
              <strong>思い出すのに時間がかかる場合？</strong><br />
              少し待つ余裕を持ちましょう。焦らず、一呼吸置くと答えが出ることがあります。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(6)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 8 (画像表示→隠す→回答) ========== -->
  <div
    class="modal fade"
    id="modalQuestion8"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問8</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- Step1: 画像を提示する画面 -->
          <div id="question8Step1">
            <div class="question">
              これから5つの品物を見せます。<br />
              (例: 時計、鍵、タバコ、ペン、硬貨)
            </div>
            <div class="item-images">
              <!-- 実際の画像パスに差し替えてください -->
              <img src="{{ asset('storage/watch.png') }}" alt="時計" />
              <img src="{{ asset('storage/key.png') }}" alt="鍵" />
              <img src="{{ asset('storage/cigarette.png') }}" alt="タバコ" />
              <img src="{{ asset('storage/pen.png') }}" alt="ペン" />
              <img src="{{ asset('storage/coin.png') }}" alt="硬貨" />
            </div>
            <div class="advice alert alert-secondary">
              見せた後、一定時間(例: 5秒)が過ぎたら隠して回答を求めます。
            </div>
            <button
              class="btn btn-primary btn-block"
              onclick="showQuestion8Step2()">
              隠して回答へ
            </button>
          </div>

          <!-- Step2: 回答する画面(隠した状態) -->
          <div id="question8Step2" style="display: none;">
            <div class="question">
              何があったか言ってください。
            </div>
            <p>※正解数を選択してください (0～5)</p>
            <select class="form-control" id="itemCountSelect">
              <option value="0">0個正解</option>
              <option value="1">1個正解</option>
              <option value="2">2個正解</option>
              <option value="3">3個正解</option>
              <option value="4">4個正解</option>
              <option value="5">5個正解</option>
            </select>
            <div class="advice alert alert-secondary mt-3">
              相互に無関係な物を選ぶのがポイント。関連物は避けてください。
            </div>
            <button
              class="btn btn-primary btn-block mt-3"
              onclick="submitQuestion8()">
              次へ
            </button>
          </div>

          <div class="extra-info mt-3">
            <h6>【設問8のポイント：5つの物品記銘】</h6>
            <p>
              <strong>どんな物がいい？</strong><br />
              携帯電話など馴染みが薄い物は避け、時計や鍵など誰でも知っている物を選びましょう。
            </p>
            <p>
              <strong>提示するときの注意？</strong><br />
              「これは◯◯ですね」と1つずつ説明しながら見せ、最後に隠します。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(7)">
            戻る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 9 ========== -->
  <div
    class="modal fade"
    id="modalQuestion9"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問9</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question">
            知っている野菜の名前をできるだけ多く言ってください。
          </div>
          <p>該当する個数の区分を選択してください。</p>
          <select class="form-control" id="vegetableSelect">
            <option value="0">5個以下</option>
            <option value="1">6個</option>
            <option value="2">7個</option>
            <option value="3">8個</option>
            <option value="4">9個</option>
            <option value="5">10個以上</option>
          </select>
          <div class="advice alert alert-secondary mt-3">
            同じ野菜名を繰り返しても記録し、後で重複分を差し引いてください。
          </div>

          <!-- ワンポイントアドバイス（設問9） -->
          <div class="extra-info">
            <h6>【設問9のポイント：野菜の名前】</h6>
            <p>
              <strong>なぜ野菜？</strong><br />
              地域差や性差が比較的少なく、男女問わず答えやすい題材とされています。
            </p>
            <p>
              <strong>平均個数は？</strong><br />
              認知症の方は平均5個前後、健常高齢者だと10個程度が一般的です。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary btn-prev"
            onclick="prevModal(8)">
            戻る
          </button>
          <button
            type="button"
            class="btn btn-primary btn-next"
            onclick="submitQuestion9()">
            結果を見る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== 結果モーダル ========== -->
  <div class="modal fade" id="modalResult" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">結果</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 id="scoreDisplay" class="text-center"></h4>
          <p id="resultText" class="text-center font-weight-bold"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery & Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- もし桜エフェクトを適用するなら以下を読み込み -->
  <script src="{{ asset('js/jquery-sakura.min.js') }}"></script>

  <script>
    // ==============================
    // 桜エフェクト（必要な場合のみ）
    // ==============================
    $(function() {
      $('body').sakura({
        className: 'sakura',
      });
    });

    // ==============================
    // スコア管理用変数
    // ==============================
    let score = 0;
    let correctAge = null; // 計算した年齢を保持

    // ==============================
    // 生年月日→年齢を計算
    // ==============================
    function calculateAge() {
      // 元号・和暦年取得
      const era = document.getElementById('birthEra').value;
      const y = parseInt(document.getElementById('birthYear').value, 10);
      const m = parseInt(document.getElementById('birthMonth').value, 10);
      const d = parseInt(document.getElementById('birthDay').value, 10);

      // 和暦→西暦変換
      let year = 0;
      if (era === 'showa') {
        year = 1925 + y; // 昭和1年=1926年
      } else if (era === 'heisei') {
        year = 1988 + y; // 平成1年=1989年
      } else if (era === 'reiwa') {
        year = 2018 + y; // 令和1年=2019年
      }

      // 数値チェック
      if (isNaN(year) || isNaN(m) || isNaN(d)) {
        alert('生年月日を正しく入力してください。');
        return;
      }

      // 範囲チェック
      if (year < 1900 || year > 2100 || m < 1 || m > 12 || d < 1 || d > 31) {
        alert('生年月日の範囲が不正です。');
        return;
      }

      // 実在日付チェック
      const birthDate = new Date(year, m - 1, d);
      if (isNaN(birthDate.getTime())) {
        alert('実在しない日付のようです。正しく入力してください。');
        return;
      }

      const now = new Date();
      let age = now.getFullYear() - birthDate.getFullYear();
      const thisYearBirthday = new Date(
        now.getFullYear(),
        birthDate.getMonth(),
        birthDate.getDate()
      );
      if (now < thisYearBirthday) {
        age--;
      }
      if (age > 120) {
        alert('入力された生年月日から算出された年齢が120歳を超えています。');
        return;
      } else if (age < 0) {
        alert('未来の生年月日です。正しい値を入力してください。');
        return;
      }
      correctAge = age;
      document.getElementById('calculatedAgeSpan').textContent = String(age);
      document.getElementById('calculatedAgeArea').style.display = 'block';
      document.getElementById('startQuizBtn').disabled = false;
    }

    // ==============================
    // 検査開始
    // ==============================
    function startQuiz() {
      // イントロ画面を隠す
      document.getElementById('introSection').style.display = 'none';

      // 設問1を開く
      $('#modalQuestion1').modal('show');

      // 設問1モーダルの文言に"計算した年齢"を表示
      if (correctAge !== null) {
        document.getElementById('calculatedAgeDisplay').textContent = String(correctAge);
      }
    }

    // ==============================
    // handleAnswer(加点, 次のモーダル番号)
    // ==============================
    function handleAnswer(scoreToAdd, nextModalNum) {
      score += scoreToAdd;
      $('#modalQuestion' + (nextModalNum - 1)).modal('hide');
      $('#modalQuestion' + nextModalNum).modal('show');
    }

    // ==============================
    // submitQuestion2: 設問2の処理
    // ==============================
    function submitQuestion2() {
      // year / month / day / weekday
      const yearScore = parseInt(document.getElementById('yearSelect').value, 10);
      const monthScore = parseInt(document.getElementById('monthSelect').value, 10);
      const dayScore = parseInt(document.getElementById('daySelect').value, 10);
      const weekdayScore = parseInt(document.getElementById('weekdaySelect').value, 10);

      score += (yearScore + monthScore + dayScore + weekdayScore);

      $('#modalQuestion2').modal('hide');
      $('#modalQuestion3').modal('show');
    }

    // ==============================
    // submitQuestion8: 設問8の処理
    // ==============================
    function showQuestion8Step2() {
      // 画像を隠し、回答画面を表示
      document.getElementById('question8Step1').style.display = 'none';
      document.getElementById('question8Step2').style.display = 'block';
    }

    function submitQuestion8() {
      const count = parseInt(document.getElementById('itemCountSelect').value, 10);
      let point = 0;
      if (count === 5) {
        point = 3;
      } else if (count === 4) {
        point = 2;
      } else if (count >= 1) {
        point = 1;
      } else {
        point = 0;
      }
      score += point;

      $('#modalQuestion8').modal('hide');
      $('#modalQuestion9').modal('show');
    }

    // ==============================
    // submitQuestion9: 設問9の処理
    // ==============================
    function submitQuestion9() {
      const vegetableScore = parseInt(document.getElementById('vegetableSelect').value, 10);
      score += vegetableScore;
      showResult(); // 結果表示
    }

    // ==============================
    // 戻るボタン
    // ==============================
    function prevModal(questionNumber) {
      $('#modalQuestion' + questionNumber).modal('hide');
      $('#modalQuestion' + (questionNumber - 1)).modal('show');
    }

    // ==============================
    // showResult: 最終結果
    // ==============================
    function showResult() {
      let resultText = '';
      if (score >= 20) {
        resultText = '異常なし';
      } else if (score >= 16) {
        resultText = '認知症の疑いあり';
      } else if (score >= 11) {
        resultText = '中程度の認知症';
      } else if (score >= 5) {
        resultText = 'やや高度の認知症';
      } else {
        resultText = '高度の認知症';
      }

      document.getElementById('scoreDisplay').innerText = `合計スコア: ${score} 点`;
      document.getElementById('resultText').innerText = `結果: ${resultText}`;

      $('#modalResult').modal('show');
    }
  </script>
</body>

</html>
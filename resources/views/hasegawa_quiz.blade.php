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

    <!-- 生年月日入力フォーム (プルダウン: 西暦年 + 月 + 日) -->
    <div class="form-inline mb-3">
      <label for="birthYear" class="mr-2">西暦:</label>
      <select class="form-control mr-2" id="birthYear" style="width: 140px;"></select>

      <label for="birthMonth" class="mr-2">月:</label>
      <select class="form-control mr-2" id="birthMonth" style="width: 90px;">
        @for ($i = 1; $i <= 12; $i++)
          <option value="{{ $i }}">{{ $i }}月</option>
          @endfor
      </select>

      <label for="birthDay" class="mr-2">日:</label>
      <select class="form-control" id="birthDay" style="width: 90px;">
        @for ($d = 1; $d <= 31; $d++)
          <option value="{{ $d }}">{{ $d }}日</option>
          @endfor
      </select>
    </div>
    <div class="d-flex mb-2" style="gap:8px;">
      <button class="btn btn-primary" onclick="calculateAge()">年齢を計算</button>
      <button class="btn btn-warning" id="birthUnknownBtn" onclick="markBirthUnknown()">生年月日が不明瞭な場合はこちら</button>
    </div>

    <!-- 生年月日不明時のフラッシュメッセージ -->
    <div id="birthUnknownFlash" style="display:none;" class="alert alert-warning" role="alert"></div>

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
          <!-- Q1スキップ通知領域（必要時にJSで内容を挿入） -->
          <div id="q1SkipNotice"></div>
          <!-- 設問2の正答日表示（検査者用） -->
          <div id="q2CorrectDate" style="margin-bottom:8px;"></div>
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
              <!--
                ここはページロード時に JavaScript で画像を差し込みます。
                画像ファイルは `storage/app/public/` に置き、公開には
                `php artisan storage:link` を実行して `/storage/` 経由で参照してください。

                デフォルトのファイル名（例）:
                  - storage/watch.png
                  - storage/key.png
                  - storage/cigarette.png
                  - storage/pen.png
                  - storage/coin.png

                画像を追加／差し替えしたらリロードすると表示されます。
              -->
            </div>
            <!-- カウントダウンメッセージ（最初から表示する） -->
            <div id="q8CountdownMessage" class="advice alert alert-info">
              画像は5秒後に自動的に隠れます。残り <span id="q8Countdown">5</span> 秒
            </div>

            <div class="advice alert alert-secondary">
              見せた後、一定時間(例: 5秒)が過ぎたら隠して回答を求めます。
            </div>

            <button
              class="btn btn-primary btn-block"
              id="q8ManualHideBtn"
              onclick="showQuestion8Step2(true)">
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
          <button type="button" class="btn btn-primary" onclick="resetQuiz()">もう一度検査</button>
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

    // ------------------------------
    // 西暦年プルダウンを初期化
    // ------------------------------
    function populateYears(startYear = 1900) {
      const yearSelect = document.getElementById('birthYear');
      yearSelect.innerHTML = '';
      const current = new Date().getFullYear();
      for (let y = current; y >= startYear; y--) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y + '年';
        yearSelect.appendChild(opt);
      }
    }

    // ページロードで西暦を生成
    document.addEventListener('DOMContentLoaded', function() {
      populateYears(1900);
      // Q8の画像を動的に配置
      try {
        populateQ8Images();
      } catch (e) {
        // noop
      }
    });

    // 生年月日不明フラグ
    let birthUnknown = false;

    // ------------------------------
    // 生年月日が不明瞭ボタンの処理
    // ------------------------------
    function markBirthUnknown() {
      birthUnknown = true;
      // フラッシュメッセージを表示
      const flash = document.getElementById('birthUnknownFlash');
      if (flash) {
        flash.innerHTML = '<strong>注意：</strong>生年月日が不明瞭なため、設問1は自動的に「0点」として扱います。保険証等で確認できる場合は必ず確認してください。<br><small>いきさつ: ご家族や保険証で年齢が確認できない場合、正確な年齢を問うことが困難なため、検査の公平性を保つためにこのオプションを設けています。</small>';
        flash.style.display = 'block';
      }

      // 検査開始ボタンを有効化して、年齢表示をダミーにセット（0扱い）
      correctAge = null;
      document.getElementById('calculatedAgeArea').style.display = 'none';
      document.getElementById('startQuizBtn').disabled = false;
    }

    // ==============================
    // スコア管理用変数
    // ==============================
    let score = 0;
    let correctAge = null; // 計算した年齢を保持

    // ==============================
    // 生年月日→年齢を計算
    // ==============================

    function calculateAge() {
      // 生年月日不明フラグが立っている場合はここで処理せずに早期return
      if (birthUnknown) {
        // 明示的に計算をスキップ
        correctAge = null;
        document.getElementById('calculatedAgeArea').style.display = 'none';
        document.getElementById('startQuizBtn').disabled = false;
        return;
      }

      // 西暦年取得（select の値は西暦）
      const y = parseInt(document.getElementById('birthYear').value, 10);
      const m = parseInt(document.getElementById('birthMonth').value, 10);
      const d = parseInt(document.getElementById('birthDay').value, 10);

      // 数値チェック
      if (isNaN(y) || isNaN(m) || isNaN(d)) {
        alert('生年月日を正しく入力してください。');
        return;
      }

      // 範囲チェック
      if (y < 1900 || y > 2100 || m < 1 || m > 12 || d < 1 || d > 31) {
        alert('生年月日の範囲が不正です。');
        return;
      }

      // 実在日付チェック
      const birthDate = new Date(y, m - 1, d);
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
      // スコア等を初期化してイントロ画面を隠す
      score = 0;
      // イントロ画面を隠す
      document.getElementById('introSection').style.display = 'none';

      // 生年月日不明フラグが立っている場合: 設問1をスキップして問1が0点となる旨を通知
      if (birthUnknown) {
        // q1SkipNotice に説明を表示
        const notice = document.getElementById('q1SkipNotice');
        if (notice) {
          notice.innerHTML = '<div class="alert alert-warning"><strong>注意：</strong>生年月日が不明瞭なため、設問1は自動的に「0点」として扱いました。保険証等で確認できる場合は必ず確認してください。</div>';
        }
        // 直接設問2を開く
        $('#modalQuestion2').modal('show');
      } else {
        // 設問1を開く
        $('#modalQuestion1').modal('show');

        // 設問1モーダルの文言に"計算した年齢"を表示
        if (correctAge !== null) {
          document.getElementById('calculatedAgeDisplay').textContent = String(correctAge);
        }
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
    // カウントダウンタイマー（設問8）
    let q8Timer = null;
    let q8Remaining = 5;

    function startQ8Countdown(seconds = 5) {
      // 既にタイマーが動作中ならクリア
      stopQ8Countdown();
      q8Remaining = seconds;
      const span = document.getElementById('q8Countdown');
      if (span) span.textContent = String(q8Remaining);

      q8Timer = setInterval(() => {
        q8Remaining -= 1;
        if (span) span.textContent = String(q8Remaining);
        if (q8Remaining <= 0) {
          // タイマー停止
          stopQ8Countdown();
          // 自動で画像を隠して回答へ遷移
          showQuestion8Step2(false);
        }
      }, 1000);
    }

    // ==============================
    // 設問8の画像を動的に読み込んで表示する
    // 使い方: storage/app/public に画像を置き、/storage/<filename> で参照します。
    // 配列 `q8ImageNames` に表示したいファイル名(5つ推奨)を入れてください。
    // ==============================
    const q8ImageNames = ['watch.png', 'key.png', 'cigarette.png', 'pen.png', 'coin.png'];

    function populateQ8Images() {
      const container = document.querySelector('.item-images');
      if (!container) return;
      container.innerHTML = '';

      q8ImageNames.forEach(name => {
        const img = document.createElement('img');
        img.src = '/storage/' + name;
        img.alt = name.replace(/\.[^/.]+$/, '');
        img.style.maxWidth = '100%';
        img.style.height = 'auto';
        img.onerror = function() {
          // 画像がない場合はプレースホルダーを表示
          const fallback = document.createElement('div');
          fallback.className = 'q8-placeholder';
          fallback.textContent = img.alt;
          fallback.style.cssText = 'display:flex;align-items:center;justify-content:center;background:#f7f7f7;border:1px dashed #ddd;color:#666;padding:12px;height:110px;';
          if (img.parentNode) img.parentNode.replaceChild(fallback, img);
        };
        container.appendChild(img);
      });
    }

    function stopQ8Countdown() {
      if (q8Timer !== null) {
        clearInterval(q8Timer);
        q8Timer = null;
      }
      const span = document.getElementById('q8Countdown');
      if (span) span.textContent = '0';
    }
    /**
     * showQuestion8Step2(manual = true)
     * manual が true の場合はユーザーが手動でボタンを押した扱いとし、
     * タイマーを停止する。false の場合は自動でタイマーから呼ばれた。
     */
    function showQuestion8Step2(manual = true) {
      // タイマーが動いていれば停止
      stopQ8Countdown();

      // 画像を隠し、回答画面を表示
      const step1 = document.getElementById('question8Step1');
      const step2 = document.getElementById('question8Step2');
      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = 'block';
      // 予防的にカウントダウンメッセージを非表示
      const msg = document.getElementById('q8CountdownMessage');
      if (msg) msg.style.display = 'none';
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
      // 特殊ケース: 設問2の戻る（questionNumber===1）が呼ばれた際に
      // 生年月日不明フラグが立っている場合は設問1は存在しないため
      // イントロに戻す振る舞いにする
      if (questionNumber === 1 && birthUnknown) {
        $('#modalQuestion2').modal('hide');
        document.getElementById('introSection').style.display = 'block';
        // 保留表示などをクリア
        const notice = document.getElementById('q1SkipNotice');
        if (notice) notice.innerHTML = '';
        return;
      }

      $('#modalQuestion' + questionNumber).modal('hide');
      $('#modalQuestion' + (questionNumber - 1)).modal('show');
    }

    // モーダル表示/非表示で設問8のカウントダウンを管理
    $(document).ready(function() {
      $('#modalQuestion8').on('shown.bs.modal', function() {
        // ステップ1を初期状態にしてメッセージを表示
        const step1 = document.getElementById('question8Step1');
        const step2 = document.getElementById('question8Step2');
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        const msg = document.getElementById('q8CountdownMessage');
        if (msg) msg.style.display = 'block';
        startQ8Countdown(5);
      });

      $('#modalQuestion8').on('hidden.bs.modal', function() {
        // モーダル閉鎖時は必ずタイマーを停止して表示をリセット
        stopQ8Countdown();
        const step1 = document.getElementById('question8Step1');
        const step2 = document.getElementById('question8Step2');
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        const msg = document.getElementById('q8CountdownMessage');
        if (msg) msg.style.display = 'block';
      });

      // 設問2モーダルが表示されたら、検査者用に正しい日付を表示する
      $('#modalQuestion2').on('shown.bs.modal', function() {
        const now = new Date();
        const yyyy = now.getFullYear();
        const mm = now.getMonth() + 1;
        const dd = now.getDate();
        const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        const wd = weekdays[now.getDay()];
        const display = `<div class="alert alert-light"><strong>正答:</strong> ${yyyy}年 ${mm}月 ${dd}日 （${wd}）</div>`;
        const el = document.getElementById('q2CorrectDate');
        if (el) el.innerHTML = display;

        // (任意) 検査者の手間を減らすため、正解の option を選択状態にしておく
        try {
          document.getElementById('yearSelect').value = '1';
          document.getElementById('monthSelect').value = '1';
          document.getElementById('daySelect').value = '1';
          const weekdayMap = {
            0: '日',
            1: '月',
            2: '火',
            3: '水',
            4: '木',
            5: '金',
            6: '土'
          };
          document.getElementById('weekdaySelect').value = '1';
        } catch (e) {
          // noop
        }
        // 生年月日不明で設問1がスキップされた場合、戻るボタンはイントロへ戻す仕様にするため無効化
        try {
          const prevBtn = document.querySelector('#modalQuestion2 .btn-prev');
          if (prevBtn) prevBtn.disabled = birthUnknown;
        } catch (e) {
          // noop
        }
      });
    });

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

    // ==============================
    // resetQuiz: 検査を最初からやり直す
    // ==============================
    function resetQuiz() {
      // すべてのモーダルを閉じる
      $('.modal').modal('hide');
      stopQ8Countdown();

      // 状態リセット
      score = 0;
      correctAge = null;
      birthUnknown = false;

      // UI を初期状態に戻す
      document.getElementById('introSection').style.display = 'block';
      const flash = document.getElementById('birthUnknownFlash');
      if (flash) {
        flash.style.display = 'none';
        flash.innerHTML = '';
      }
      const startBtn = document.getElementById('startQuizBtn');
      if (startBtn) startBtn.disabled = true;

      const calcArea = document.getElementById('calculatedAgeArea');
      if (calcArea) calcArea.style.display = 'none';

      const q1Notice = document.getElementById('q1SkipNotice');
      if (q1Notice) q1Notice.innerHTML = '';

      // 設問8の表示を初期化
      const step1 = document.getElementById('question8Step1');
      const step2 = document.getElementById('question8Step2');
      if (step1) step1.style.display = 'block';
      if (step2) step2.style.display = 'none';
      const q8msg = document.getElementById('q8CountdownMessage');
      if (q8msg) q8msg.style.display = 'block';

      // リセット後、年のプルダウンは残すが選択を最新に
      try {
        populateYears(1900);
      } catch (e) {
        // noop
      }
    }
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f2c6d8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/pwa/icon-192.png') }}">
    <title>心桜 認知症チェック</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>

<body>
    <div id="networkStatus" class="network-status" role="alert" aria-live="polite">
        オフラインです。通信環境をご確認ください。
    </div>
    <div id="globalLoading" class="loading-overlay" aria-live="polite" aria-busy="true">
        <div class="loading-spinner" aria-hidden="true"></div>
        <div class="loading-text">読み込み中...</div>
    </div>
    <div class="theme-toggle">
        <button type="button" class="btn btn-outline-primary" id="themeToggleBtn">ダークモード</button>
    </div>
    <div id="petalLayer" aria-hidden="true"></div>
    <div class="container">
        <header>
            <img src="{{ asset('storage/画像1.png') }}" alt="ロゴ" class="logo">
        </header>
        <main class="text-center mt-5">
            <div class="title-wrapper">
                <span class="title-main">心桜</span>
                <span class="title-sub">認知症チェック</span>
            </div>
            <p class="delayed-text mt-4">大切な人の異常や認知症の早期発見により<br>いち早く適切な医療や介護に繋げ、住み慣れた地域で支える。</p>
            <img src="{{ asset('storage/63923.png') }}" alt="イラスト" class="img-fluid img-custom mt-3">
            <br>
            <a href="{{ url('/quiz') }}" class="btn btn-custom btn-lg mt-3">簡易チェックを実施する</a>
        </main>
    </div>
    <button
        type="button"
        class="home-consult-fab"
        id="homeConsultFab"
        aria-controls="homeConsultPanel"
        aria-expanded="false"
        aria-label="各種相談を開く">
        <span class="home-consult-label">各種相談</span>
        <span class="home-consult-icon"><i class="fas fa-comments" aria-hidden="true"></i></span>
    </button>
    <div id="homeConsultPanel" class="home-consult-panel" role="dialog" aria-live="polite" hidden>
        <div class="home-consult-title">各種相談</div>
        <p class="home-consult-text">気になるときは、開いて確認できます。</p>
        <div class="home-consult-accordion">
            <details class="home-consult-item">
                <summary>🏥 医療機関に相談</summary>
                <div class="home-consult-item-body">
                    <h6>はじめに</h6>
                    <p>物忘れや段取りの変化は、年齢だけでなく疲れ・睡眠不足・ストレス・体調などでも起こります。<br />
                        このアプリの結果は「診断」ではありませんが、早めに相談しておくと安心につながることがあります。</p>
                    <h6>相談の進め方（かんたん3ステップ）</h6>
                    <p>① 受診の予約（または受付確認）<br />
                        病院へ電話して「もの忘れ（認知機能）の相談で受診したい」ことを伝え、<br />
                        ・予約が必要か<br />
                        ・何を持参するか<br />
                        ・家族の同席が必要か<br />
                        を確認します。</p>
                    <p>② 受診メモを準備（短くてOK）<br />
                        受診のときに伝えたいことを、箇条書きでメモしておくと安心です。<br />
                        （例：いつ頃から／どんな場面で／どれくらいの頻度／困っていること）</p>
                    <p>③ 受診して相談<br />
                        医師が話を聞き、必要に応じて検査や今後の過ごし方を一緒に考えてくれます。</p>
                    <h6>電話でそのまま使えるテンプレ（本人）</h6>
                    <p>「お電話失礼します。最近、物忘れや段取りのしにくさが増えて少し心配です。<br />
                        もの忘れ（認知機能）の相談で受診したいのですが、予約は必要でしょうか。<br />
                        持ち物や事前に準備しておくことがあれば教えてください。」</p>
                    <h6>電話でそのまま使えるテンプレ（家族）</h6>
                    <p>「家族のことで相談です。物忘れや生活の困りごとが増えてきて心配しています。<br />
                        受診の流れ（予約の要否、持ち物、同席の必要など）を教えてください。」</p>
                    <h6>持っていくと安心なもの（最低限）</h6>
                    <ul>
                        <li>保険証（医療証があれば一緒に）</li>
                        <li>お薬手帳（または薬が分かるもの）</li>
                        <li>受診メモ</li>
                    </ul>
                    <h6>受診メモ</h6>
                    <ul>
                        <li>いつ頃から：＿＿＿＿（例：3か月前から）</li>
                        <li>困る場面：＿＿＿＿（例：支払い／料理／予定／道順）</li>
                        <li>頻度：＿＿＿＿（例：週に数回）</li>
                        <li>生活で困っていること：＿＿＿＿</li>
                        <li>心配な出来事：＿＿＿＿（例：火、薬、外出、詐欺など）</li>
                    </ul>
                    <h6>アプリの点数が低いほど（目安）</h6>
                    <p>点数が低いほど、「再チェック」よりも“早めの相談”が安心につながりやすい目安になります。<br />
                        迷うときは、まずは相談の準備だけでもOKです。</p>
                </div>
            </details>

            <details class="home-consult-item">
                <summary>🏢 地域包括に相談</summary>
                <div class="home-consult-item-body">
                    <h6>地域包括支援センターって、どんな所？</h6>
                    <p>地域包括支援センターは、本人や家族のための総合相談窓口です。<br />
                        介護保険だけでなく、<br />
                        「認知症かも」「生活が不安」「家族だけでは大変」<br />
                        といった悩みを、整理して次の一歩につなげるための相談ができます。</p>
                    <h6>探し方</h6>
                    <p>市町村サイトで「地域包括支援センター 電話」を検索してください。</p>
                    <h6>相談の進め方（かんたん3ステップ）</h6>
                    <p>① 電話で相談（最短）<br />
                        「物忘れが心配」「生活の困りごとがある」と伝えるだけで大丈夫です。</p>
                    <p>② 状況を一緒に整理<br />
                        困っていること（薬／火／金銭／外出／入浴など）を一緒に整理します。</p>
                    <p>③ 必要な支援へつなぐ<br />
                        受診先の相談、介護保険、利用できる支援・見守りなど、状況に合わせて案内されます。</p>
                    <h6>電話でそのまま使えるテンプレ</h6>
                    <p>「お電話失礼します。本人（家族）の物忘れが少し心配で、生活でも困りごとが出てきました。<br />
                        受診先の相談や、介護保険のこと、今すぐできる支援について相談したいです。<br />
                        担当の方につないでいただけますか？」</p>
                    <h6>相談前にメモしておくとスムーズ（短くてOK）</h6>
                    <ul>
                        <li>本人：年齢／住まい（独居・同居）</li>
                        <li>困りごと：＿＿＿＿（例：服薬、火、金銭、外出、入浴）</li>
                        <li>受診状況：未受診／受診済み（かかりつけ医：あり・なし）</li>
                        <li>家族の状況：支援できる頻度（毎日／週末／遠方など）</li>
                    </ul>
                    <h6>こんな時に特におすすめ</h6>
                    <ul>
                        <li>受診先に迷う</li>
                        <li>生活の不安（薬・火・外出など）が先に来ている</li>
                        <li>介護保険の申請が必要か分からない</li>
                        <li>家族の負担が大きくなっている</li>
                    </ul>
                </div>
            </details>

            <details class="home-consult-item">
                <summary>🚗 運転の相談</summary>
                <div class="home-consult-item-body">
                    <h6>はじめに（責めない・安全を大切に）</h6>
                    <p>運転の話題は、とても大切で、同時に言いづらいことでもあります。<br />
                        ここでは「やめる／続ける」を決めつけるのではなく、<br />
                        <strong>安全のために“相談という形で整理する”</strong>ことを目的にしています。</p>
                    <h6>スコアに応じた目安（表示ルール：アプリ実装向け）</h6>
                    <p>※点数は本アプリのチェック結果（目安）で、診断ではありません。</p>
                    <ul>
                        <li>20-30点：運転に不安がある場合は、家族や医療機関に相談</li>
                        <li>16-19点：不安があれば早めに見直し、相談窓口の利用を検討</li>
                        <li>11-15点：安全のため運転を慎重に見直し（相談窓口へ）</li>
                        <li>5-10点／0-4点：運転中止を含めて強く検討＋相談窓口・自主返納制度の案内</li>
                    </ul>
                    <h6>相談先（電話）</h6>
                    <p>安全運転に不安がある場合、都道府県警察の<strong>安全運転相談窓口（#8080）</strong>に相談できます。</p>
                    <h6>電話でそのまま使えるテンプレ（本人）</h6>
                    <p>「運転に少し不安が出てきました。安全のために、運転の見直しについて相談したいです。<br />
                        相談先や手続き、自主返納について教えてください。」</p>
                    <h6>電話でそのまま使えるテンプレ（家族）</h6>
                    <p>「家族の運転が心配です。本人の安全のために、運転の見直しや相談先について教えてください。<br />
                        自主返納の手続きや、必要な準備も知りたいです。」</p>
                    <h6>自主返納について（やさしい補足）</h6>
                    <p>免許は自主返納を検討でき、返納後に運転経歴証明書を申請できる場合があります。<br />
                        「加害者にならないためにも」、迷った時点で早めに相談することが安全につながります。</p>
                    <h6>返納を考えるときの大事なポイント（不安を減らす）</h6>
                    <p>返納は「終わり」ではなく、移動の代わりを一緒に考えることが大切です。</p>
                    <ul>
                        <li>通院・買い物：家族送迎、バス、タクシー、配達サービス等</li>
                        <li>本人の気持ち：否定せず「安全のために一緒に相談しよう」と伝える</li>
                    </ul>
                </div>
            </details>

            <details class="home-consult-item">
                <summary>🧾 介護保険の流れ</summary>
                <div class="home-consult-item-body">
                    <h6>はじめに</h6>
                    <p>介護保険は「大変そう」と感じやすいですが、流れが分かれば、意外と一歩ずつ進められます。<br />
                        困りごとが増えてきたときに、生活を楽にするための仕組みとして使えます。</p>
                    <h6>申請はどこにする？</h6>
                    <ul>
                        <li>住んでいる市区町村の介護保険担当窓口（介護保険課など）</li>
                        <li>迷う場合は、まず地域包括支援センターに相談してもOKです（案内してもらえます）</li>
                    </ul>
                    <h6>申請できる人</h6>
                    <ul>
                        <li>本人</li>
                        <li>家族（相談しながら進められます。早めに動くほど安心です）</li>
                    </ul>
                    <h6>全体の流れ（これだけ覚えればOK）</h6>
                    <p>① 申請（市区町村の介護保険担当窓口へ）<br />
                        ② 認定調査（調査員が心身の状態を確認）<br />
                        ③ 主治医意見書（市区町村から医師へ依頼）<br />
                        ④ 審査・認定（要支援／要介護などが決まる）<br />
                        ⑤ ケアプラン作成（利用するサービスの計画）<br />
                        ⑥ サービス利用開始</p>
                    <h6>市役所（介護保険課）に電話するテンプレ</h6>
                    <p>「介護保険の申請について確認したくお電話しました。<br />
                        家族（本人）の生活の困りごとが増えてきたため、要介護認定の申請を検討しています。<br />
                        申請はどの窓口で、何を持参すればよいでしょうか。手続きの流れも教えてください。」</p>
                    <h6>申請前にメモしておくと安心（短くてOK）</h6>
                    <ul>
                        <li>本人の情報：住所・保険証</li>
                        <li>困りごと：＿＿＿＿（例：転倒、服薬、火、金銭、外出、入浴）</li>
                        <li>家族の支援状況：＿＿＿＿（例：同居、週末のみ、遠方）</li>
                        <li>受診状況：＿＿＿＿（診断の有無は後からでもOK）</li>
                    </ul>
                    <h6>受け入れやすい伝え方（家族向けの一言）</h6>
                    <p>「できないことが増えた」より、<br />
                        「安全のために、生活が回る仕組みを一緒に作りたい」<br />
                        と伝える方が、本人も受け入れやすいことが多いです。</p>
                </div>
            </details>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
</body>

</html>

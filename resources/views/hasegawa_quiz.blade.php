<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>改定長谷川式簡易知能評価スケール（HDS-R）検査</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container { max-width: 600px; margin-top: 20px; }
        .question, .advice, .instructions { margin-bottom: 20px; }
        .btn-option, .btn-start { margin-right: 10px; margin-bottom: 10px; }
        .hidden { display: none; }
    </style>
</head>
<body>
<div class="container">
    <h2>改定長谷川式簡易知能評価スケール（HDS-R）検査</h2>
    <div id="instructions" class="instructions">
    <h5>検査の順番について</h5>
        <p>設問の順序は固定されておらず、患者様が話しやすい順に進めていただいて構いません。ただし、設問4から7については、検査の構造上、指定された順序でお願いいたします。</p>
        <h5>検査の導入にあたっての注意</h5>
        <p>検査を開始する際は、「最近、物忘れでお困りではないですか？」などとやさしく尋ねることをお勧めします。患者様がリラックスして検査に臨めるよう、心を配ってください。</p>
        <h5>検査を終了した後の注意点</h5>
        <p>検査が終わったら、「お疲れ様でした。どうですか、疲れましたか？」と声をかけ、患者様が安心できるように努めてください。</p>
        <button class="btn btn-primary btn-start" onclick="startQuiz()">開始する</button>
        </div>
    <div id="quizContainer" class="hidden"></div>
    <div id="result" class="hidden"></div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>

let score = 0;
let currentQuestionIndex = 0;
const quizContainer = document.getElementById('quizContainer');

    const questions = [
        {
            question: "設問1 お歳はいくつですか？（2年までの誤差は正解）",
            options: [
                { text: "正解", score: 1 },
                { text: "不正解", score: 0 }
            ],
            advice: "数え年で答える人もおり、誕生日を迎えているかどうかで誤差が生まれる可能性があります。※ちなみに生年月日が答えれても年齢が答えられなければ不正解になります。"
        },
        {
            question: "設問2 今日は何年の何月何日ですか？何曜日ですか？",
            render: function() {
                quizContainer.innerHTML = `<div class="question"><h4>${this.question}</h4></div>`;
                const html = `
                    <div class="form-group">
                        <label for="year-select">年</label>
                        <select class="form-control" id="year-select">
                            <option value="0">不正解</option>
                            <option value="1">正解</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month-select">月</label>
                        <select class="form-control" id="month-select">
                            <option value="0">不正解</option>
                            <option value="1">正解</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="day-select">日</label>
                        <select class="form-control" id="day-select">
                            <option value="0">不正解</option>
                            <option value="1">正解</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weekday-select">曜日</label>
                        <select class="form-control" id="weekday-select">
                            <option value="0">不正解</option>
                            <option value="1">正解</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" onclick="submitDateAnswers()">次へ</button>
                `;
                quizContainer.innerHTML += html;
                quizContainer.innerHTML += `<div class="advice alert alert-secondary mt-3">${this.advice}</div>`;
            },
            advice: "時間の見当識に関する質問です。質問の順番は関係ありません。逆から聞くとうまく良く場合も多いです。例)「今日は何月何日でしたか？」等々"
        },
                {
                    question: "設問3 私たちが今いるところは、どこですか？",
                    options: [
                        { text: "自発的に正解", score: 2 },
                        { text: "ヒント後に正解", score: 1 },
                        { text: "不正解", score: 0 }
                    ],
                    advice: "返答できなければ約5秒おいて「家ですか？」「病院ですか？」「施設ですか？」とヒントを出してください。病院、施設名を答える必要はありません。本質的に理解できていれば正解とします。3つのヒントは1例であり、次回の質問時に「家ですか？」「デイサービスセンターですか？」「公民館ですか？」と変更しても構いません。"
                },
                {
    question: "設問4 これから言う3つの言葉を言ってみてください。また、後で聞きますのでよく覚えておいてください。（'桜', '猫', '電車'）または（'梅', '犬', '自動車'）",
    options: [
        { text: "全て言えた", score: 3 },
        { text: "2つ言えた", score: 2 },
        { text: "1つ言えた", score: 1 },
        { text: "言えない", score: 0 }
    ],
    advice: "この3つの言葉は後の設問7で再度答えていただくため、言えた事を確認したらもう一度3つの言葉を覚えてもらう。これを上限3回までとして、繰り返し言って覚えてもらう。仮に3回繰り返しても2つしか覚えられない際は設問7で「2つの言葉がありましたね」というように尋ねる。"
},
{
    question: "100から7を順番に引いてください。（93、86、79...と続けれるだけ回答してもらって構いません）",
    options: [
        { text: "93と正答", score: 1 },
        { text: "続けて86と正答", score: 2 },
        { text: "不正解", score: 0 }
    ],
    advice: "最初の引き算に失敗した場合は、そこで不正解として打ち切ります。引き算は93で正答した場合、「そこから、また7を引くと？」という感じで再度質問を行う。注意点として「93引く7は？」と言ってはいけない。100から7を引くと93になるが、その93という数字を覚えているうえで更に7を引くという作業記憶課題であるため、質問者は93という数字を言ってはならない。86と正答があれば問題なし。"
},
{
    question: "私がこれから言う数字を逆から言ってください。（たとえば、「6-8-2」と言ったら、「2-8-6」と答えます。）",
    options: [
        { text: "全て正解", score: 1 },
        { text: "不正解", score: 0 }
    ],
    advice: "数字はゆっくりと提示し、1秒間隔くらいで読み上げます。事前に練習問題を入れることが推奨されます。"
},
{
    question: "先ほど覚えてもらった言葉をもう一度言ってみてください。",
    options: [
        { text: "全て正解", score: 3 },
        { text: "部分的正解", score: 2 },
        { text: "ヒント後に正解", score: 1 },
        { text: "不正解", score: 0 }
    ],
    advice: "自発的に答えられた場合は各2点。ヒントを与えた後の正解は1点です。"
},
{
    question: "これから5つの品物を見せます。それを隠しますので、何があったか言ってください。（例: 時計、鍵、タバコ、ペン、硬貨）",
    options: [
        { text: "5つ全て正解", score: 3 },
        { text: "4つ正解", score: 2 },
        { text: "3つ以下正解", score: 1 },
        { text: "不正解", score: 0 }
    ],
    advice: "相互に無関係な物品を選び、1つずつ名前を言いながら提示します。"
},
{
    question: "知っている野菜の名前をできるだけ多く言ってください。",
    options: [
        { text: "10個以上", score: 5 },
        { text: "9個", score: 4 },
        { text: "8個", score: 3 },
        { text: "7個", score: 2 },
        { text: "6個", score: 1 },
        { text: "5個以下", score: 0 }
    ],
    advice: "この設問は言語の流ちょう性を測るためのものです。同じ野菜を繰り返しても記録し、後で重複を減点します。"
},
];
function startQuiz() {
    document.getElementById('instructions').style.display = 'none';
    document.getElementById('quizContainer').style.display = 'block';
    showQuestion(currentQuestionIndex);
}
function showQuestion(index) {
        if (index < questions.length) {
            const question = questions[index];
            if(question.render) {
                question.render();
            } else {
                // 通常の質問表示ロジック
                quizContainer.innerHTML = `<div class="question"><h4>${question.question}</h4></div>`;
                const optionsEl = document.createElement('div');
                question.options.forEach((option, optionIndex) => {
                    const button = document.createElement('button');
                    button.innerHTML = option.text;
                    button.classList.add('btn', 'btn-option', 'btn-info');
                    button.onclick = () => handleAnswer(option.score, index);
                    optionsEl.appendChild(button);
                });
                quizContainer.appendChild(optionsEl);

                const adviceEl = document.createElement('div');
                adviceEl.className = 'advice alert alert-secondary mt-3';
                adviceEl.textContent = question.advice;
                quizContainer.appendChild(adviceEl);
            }
        } else {
            showResults();
        }
    }

    window.submitDateAnswers = function() {
        const yearScore = parseInt(document.getElementById('year-select').value);
        const monthScore = parseInt(document.getElementById('month-select').value);
        const dayScore = parseInt(document.getElementById('day-select').value);
        const weekdayScore = parseInt(document.getElementById('weekday-select').value);
        score += yearScore + monthScore + dayScore + weekdayScore;
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    };

    function handleAnswer(scoreToAdd, questionIndex) {
        score += scoreToAdd;
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }

    function showResults() {
    let resultText = '';
    if(score >= 20) {
        resultText = '異常なし';
    } else if(score >= 16) {
        resultText = '認知症の疑いあり';
    } else if(score >= 11) {
        resultText = '中程度の認知症';
    } else if(score >= 5) {
        resultText = 'やや高度の認知症';
    } else {
        resultText = '高度の認知症';
    }
    quizContainer.innerHTML = `<div class="result"><h3>あなたの合計スコアは ${score} 点です。結果: ${resultText}</h3></div>`;
}

// 最初の質問を表示
showQuestion(currentQuestionIndex);


document.addEventListener('DOMContentLoaded', function() {
    // startQuiz() 関数を直接呼び出さないように、ここは空にしておきます。
});
</script>
</body>
</html>
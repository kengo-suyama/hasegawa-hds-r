document.addEventListener('DOMContentLoaded', function() {
    const quizContainer = document.getElementById('quiz');

    const questions = [
        {
            question: "お歳はいくつですか？",
            options: [
                { text: "正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "今日は何年の何月何日ですか？何曜日ですか？",
            options: [
                { text: "年 正解", score: 1 },
                { text: "月 正解", score: 1 },
                { text: "日 正解", score: 1 },
                { text: "曜日 正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "私たちが今いるところは、どこですか？",
            options: [
                { text: "自発的に正解", score: 2 },
                { text: "ヒント後に正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "これから言う3つの言葉を言ってみてください。",
            options: [
                { text: "全て正解", score: 3 },
                { text: "2つ正解", score: 2 },
                { text: "1つ正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "100から7を順番に引いてください。",
            options: [
                { text: "5回連続で正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "私がこれから言う数字を逆から言ってください。",
            options: [
                { text: "全て正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "先ほど覚えてもらった言葉をもう一度言ってみてください。",
            options: [
                { text: "全て正解", score: 3 },
                { text: "部分的正解", score: 2 },
                { text: "ヒント後に正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
        },
        {
            question: "これから5つの品物を見せます。それを隠しますので、なにがあったか言ってください。",
            options: [
                { text: "5つ全て正解", score: 3 },
                { text: "4つ正解", score: 2 },
                { text: "3つ以下正解", score: 1 },
                { text: "不正解", score: 0 }
            ]
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
            ]
        }
    ];

    questions.forEach((item, index) => {
        const questionSection = document.createElement('div');
        questionSection.className = 'question';
        questionSection.innerHTML = `<p>${index + 1}. ${item.question}</p>`;

        item.options.forEach(option => {
            const button = document.createElement('button');
            button.innerText = option.text;
            button.onclick = () => handleAnswer(index, option.score);
            questionSection.appendChild(button);
        });

        quizContainer.appendChild(questionSection);
    });

    const resultButton = document.createElement('button');
    resultButton.innerText = 'スコアを計算';
    resultButton.onclick = calculateTotalScore;
    quizContainer.appendChild(resultButton);

    const resultDisplay = document.createElement('div');
    resultDisplay.id = 'result';
    quizContainer.appendChild(resultDisplay);

    let answers = Array(questions.length).fill(-1);

    function handleAnswer(questionIndex, score) {
        answers[questionIndex] = score;
        console.log(`Question ${questionIndex + 1} answered with score: ${score}`);
    }

    function calculateTotalScore() {
        const totalScore = answers.reduce((acc, current) => current > -1 ? acc + current : acc, 0);
        document.getElementById('result').innerText = `合計スコア: ${totalScore}`;
    }
});

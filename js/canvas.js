(function() {
    //HTML上のcanvasタグを取得
    var canvas = document.getElementById('canvas');

    //レスポンシブ対応 画面サイズでキャンパスサイズを調整します
    if (screen.width < 860) {
        canvas.width = 700 * screen.width / 860;
        canvas.height = 400 * screen.width / 860;
    }
 
    //初期値（サイズ、色、アルファ値）の決定
    var defosize = 3;
    var defocolor = "#555555";
    var defoalpha = 1.0;
    var defbackcolor = "#ffffff";
 
    //キャンバスの背景カラーを決定。 fillRectは長方形に塗るメソッド
    var ctx = canvas.getContext('2d');
    ctx.beginPath();
    ctx.fillStyle = defbackcolor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
 
    //マウス継続値の初期値、ここがポイント
    var mouseX = "";
    var mouseY = "";
 
    //スマホ用
　　var finger=new Array;
    for(var i=0;i<10;i++){
        finger[i]={
            x:0,y:0,x1:0,y1:0,
            color:"rgb("
                +Math.floor(Math.random()*16)*15+","
                +Math.floor(Math.random()*16)*15+","
                +Math.floor(Math.random()*16)*15
            +")"
        };
    }
 
    //各イベントに紐づけ
    canvas.addEventListener('mousemove', onMove, false);
    canvas.addEventListener('mousedown', onClick, false);
    canvas.addEventListener('mouseup', drawEnd, false);
    canvas.addEventListener('mouseout', drawEnd, false);
    canvas.addEventListener('touchstart', onTouchStart, false);
    canvas.addEventListener('touchmove', onTouchMove, false);
 
    //マウス動いていて、かつ左クリック時に発火。
    function onMove(e) {
        if (e.buttons === 1 || e.witch === 1) {
            var rect = e.target.getBoundingClientRect();
            var X = ~~(e.clientX - rect.left);
            var Y = ~~(e.clientY - rect.top);
            //draw 関数にマウスの位置を渡す
            draw(X, Y);
        };
    };
 
    //マウスが左クリックされると発火。
    function onClick(e) {
        if (e.button === 0) {
            var rect = e.target.getBoundingClientRect();
            var X = ~~(e.clientX - rect.left);
            var Y = ~~(e.clientY - rect.top);
            //draw 関数にマウスの位置を渡す
            draw(X, Y);
        }
    };
 
    //渡されたマウス位置を元に直線を描く関数
    function draw(X, Y) {
        ctx.beginPath();
        ctx.globalAlpha = defoalpha;
        //マウス継続値によって場合分け、直線の moveTo（スタート地点）を決定
        if (mouseX === "") {
            //継続値が初期値の場合は、現在のマウス位置をスタート位置とする
            ctx.moveTo(X, Y);
        } else {
            //継続値が初期値ではない場合は、前回のゴール位置を次のスタート位置とする
            ctx.moveTo(mouseX, mouseY);
        }
        //lineTo（ゴール地点）の決定、現在のマウス位置をゴール地点とする
        ctx.lineTo(X, Y);
        //直線の角を「丸」、サイズと色を決める
        ctx.lineCap = "round";
        ctx.lineWidth = defosize * 2;
        ctx.strokeStyle = defocolor;
        ctx.stroke();
        //マウス継続値に現在のマウス位置、つまりゴール位置を代入
        mouseX = X;
        mouseY = Y;
    };
 
    //左クリック終了、またはマウスが領域から外れた際、継続値を初期値に戻す
    function drawEnd() {
        mouseX = "";
        mouseY = "";
    }
 
    //タッチした瞬間座標を取得（スマホ）
    function onTouchStart(e) {
        e.preventDefault();
        var rect = e.target.getBoundingClientRect();
        for(var i=0;i<finger.length;i++){
            finger[i].x1 = e.touches[i].clientX-rect.left;
            finger[i].y1 = e.touches[i].clientY-rect.top;
        }
    }
 
    //タッチして動き出したら描画する
    function onTouchMove(e) {
        e.preventDefault();
        var rect = e.target.getBoundingClientRect();
        for(var i=0;i<finger.length;i++){
            finger[i].x = e.touches[i].clientX-rect.left;
            finger[i].y = e.touches[i].clientY-rect.top;
            ctx.beginPath();
            ctx.globalAlpha = defoalpha;
            ctx.moveTo(finger[i].x1,finger[i].y1);
            ctx.lineTo(finger[i].x,finger[i].y);
            //直線の角を「丸」、サイズと色を決める
            ctx.lineCap = "round";
            ctx.lineWidth = defosize * 2;
            ctx.strokeStyle = defocolor;
            ctx.stroke();
            finger[i].x1=finger[i].x;
            finger[i].y1=finger[i].y;
        }
    }
 
    //メニューのアイコン関係
    var menuIcon = document.getElementsByClassName("menuicon");
    for (i = 0; i < menuIcon.length; i++) {
        menuIcon[i].addEventListener("click", canvasMenu, false)
    }
 
    //メニューボタン管理
    function canvasMenu() {
        //id 値によって場合分け
        var thisId = this.id;
        if (thisId.indexOf("size") + 1) {
            defosize = ~~this.id.slice(4, this.id.length);
        }
        if (thisId.indexOf("color") + 1) {
            defocolor = "#" + this.id.slice(5, this.id.length);
        }
        if (thisId.indexOf("alpha") + 1) {
            defoalpha = (~~this.id.slice(5, this.id.length)) / 10;
        }
        if (thisId.indexOf("clear") + 1) {
            //全消しボタン、OKされた場合は fillRect 長方形で覆います
            if (confirm("すべて消去しますか？")) {
                ctx.beginPath();
                ctx.fillStyle = defbackcolor;
                ctx.globalAlpha = 1.0;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }
        }
    }
})();

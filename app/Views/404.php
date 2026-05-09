<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>404 Not Found</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #0f1117;
    color: #f5f7fa;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.background-glow {
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(99,102,241,.18) 0%, transparent 70%);
    filter: blur(80px);
    z-index: 0;
}

.container {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 48px;
    max-width: 560px;
}

.error-code {
    font-size: 120px;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -4px;
    background: linear-gradient(135deg, #ffffff, #7c83ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.title {
    font-size: 32px;
    font-weight: 600;
    margin-top: 16px;
}

.description {
    margin-top: 14px;
    color: #9aa4b2;
    font-size: 16px;
    line-height: 1.7;
}

.actions {
    margin-top: 32px;
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}

.btn {
    padding: 14px 22px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: .25s ease;
}

.btn-primary {
    background: #6366f1;
    color: #fff;
}

.btn-primary:hover {
    background: #7c83ff;
    transform: translateY(-2px);
}

.btn-secondary {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    color: #d7dde5;
}

.btn-secondary:hover {
    background: rgba(255,255,255,.08);
    transform: translateY(-2px);
}

.footer {
    margin-top: 40px;
    font-size: 13px;
    color: #6b7280;
}

@media (max-width: 640px) {
    .error-code {
        font-size: 90px;
    }

    .title {
        font-size: 26px;
    }

    .container {
        padding: 32px 24px;
    }
}
</style>
</head>
<body>

<div class="background-glow"></div>

<div class="container">
    <div class="error-code">404</div>


    <p class="description">
       While you're here, <b>enjoy this completely useless placeholder text</b>. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel accumsan sapien, sed dapibus tortor. Maecenas velit nulla, dapibus quis consectetur ut, molestie sit amet mauris.
    </p>


    <div class="footer">
        Qdrant Admin Panel By Zulkhaery
    </div>
</div>

</body>
</html>
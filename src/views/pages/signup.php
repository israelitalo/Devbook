<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastro</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
</head>
<body>
<header>
    <div class="container">
        <a href=""><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
    </div>
</header>
<section class="container main">
    <form method="POST" action="<?=$base;?>/cadastro">
        <?php if(!empty($msg)):?>
            <div class="msg"><?php echo $msg;?></div>
        <?php endif; ?>

        <input placeholder="Digite seu nome" class="input" type="text" name="name" required autofocus>

        <input placeholder="Digite seu e-mail" class="input" type="email" name="email" required/>

        <input placeholder="Digite sua senha" class="input" type="password" name="password" required/>

        <input placeholder="Digite sua data de nascimento" class="input" type="text" name="birthdate" id="birthdate" required/>

        <input class="button" type="submit" value="Cadastrar" />

        <a href="<?=$base;?>/login">Já tem cadastro? Faça o login</a>
    </form>
</section>

<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'),
        {
            mask:'00/00/0000'
        }
    );
</script>
</body>
</html>

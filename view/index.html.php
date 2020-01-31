
<div class="container">
	
        
        
        <?php $name = $_SESSION['login'];?> 
        <?if($_SESSION['auth']):?>
            <? echo "<span>Привет $name</span><br>";?>
            <?$link ='/add'; $button = 'Добавить новость';?>
        <?endif?>
        <?if(!$_SESSION['auth']):?>
            <?$link ='/signup'; $button = 'Регистрация';?>
        <?endif?>
        <a href="<?=$link?>"><button style="background: #056CA1; margin: 5px; color: #f0f0f0;"><?=$button?></button></a><br>
            
</div><br>
<div class="container">
    <?php foreach($article as $one):?>
        <div class="item">
            <span><?=$one['date']?></span>
            <strong><a href="/article/<?=$one['id']?>"><?=$one['title']?></a></strong>
            <div><?=$one['content']?></div>
        </div>
        <hr>
    <? endforeach ?>        
</div>

<?php 
/*
Template Name: Шаблон одиночной ставки
Template Post Type: bets
*/
?>

<?php get_header(); ?>

<main id="content" role="main">

<div class="bet-container">
        
    <div class="bet-title">
       <h3>Название ставки: </h3><h2><?php the_title(); ?></h2> 
    </div>

    <div class="bet-description">
        <h3>Описание ставки: </h3>    <?php the_content(); ?> 
    </div>

    <div class="bet-type">
        <h3>Тип ставки: </h3><?php echo get_the_terms( get_the_ID(), 'bet_type' )[0]->name; ?>
    </div>

    <div class="bet-status">
        <h3>Статус ставки: </h3><p>Неопределен</p>
    </div>

    <div class="do-bet">
        <div class="form-container">
            <H3>Форма ввода вставки</H3>
            <form id="doBetForm" action="" method="post" name="doBetForm">
                <label for="title"></label>
                <input type="number" name="bet" id="bet" min="100" max="1000" placeholder="Сделать ставку">
                <input type="hidden" name="post_id" value="<?php the_ID()?>" style="display: none; visibility: hidden; opacity: 0;">
                <input type="hidden" name="action" value="do_bet" style="display: none; visibility: hidden; opacity: 0;">
                <button type="submit" id="submit" class="go">Отправить</button>
            </div>
        </form>
        </div>
    </div>
<style>
.bet-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.bet-container div{
    display: flex;
    align-items: baseline;
    gap: 20px;
}
</style>

</div>
</main>
<?php get_footer(); ?>

<div class="aqi-tabs clearfix" style="margin-top: 15px;">

    <?php if ($is_expert) : ?>

        <div class="aqi-tab active" id="aqi_tab_new" data-tab="new_tab">
            <i class="fa fa-commenting" aria-hidden="true"></i><?php pll_e('New questions') ?>
        </div>

        <div class="aqi-tab" id="aqi_tab_participant" data-tab="participant_tab">
            <i class="fa fa-comments" aria-hidden="true"></i>
            <?php pll_e('I am participant') ?>
            <?php if ($info['chats']['unread_participant'] > 0) : ?>
                <span id="aqi_tab_indicator"><?= $info['chats']['unread_participant'] ?></span>
            <?php endif; ?>
        </div>

        <div class="aqi-tab" id="aqi_tab_answered" data-tab="answered_tab">
            <i class="fa fa-check" aria-hidden="true"></i><?php pll_e('Answered') ?>
        </div>

    <?php else : ?>

        <div class="aqi-tab active" id="aqi_tab_my" data-tab="my_tab">
            <i class="fa fa-commenting" aria-hidden="true"></i><?php pll_e('My questions') ?>
            <?php if ($info['unread'] > 0) : ?>
                <span id="aqi_tab_indicator"><?= $info['unread'] ?></span>
            <?php endif; ?>
        </div>

        <div class="aqi-tab" id="aqi_tab_add" data-tab="add_tab">
            <i class="fa fa-plus" aria-hidden="true"></i><?php pll_e('Ask') ?>
        </div>

    <?php endif; ?>

</div>


<div class="aqi-user-info clearfix" style="margin-bottom: 15px;">
    <div class="aqi-user-avatar">
        <i class="fa fa-question" aria-hidden="true"></i>
    </div>

    <?php if ($is_expert) : ?>

        <div class="aqi-tab-content" id="new_tab">
            <?php include __DIR__ . '/new_questions.php' ?>
        </div>

        <div class="aqi-tab-content" id="participant_tab" style="display: none;">
            <?php include __DIR__ . '/participant.php' ?>
        </div>

        <div class="aqi-tab-content" id="answered_tab" style="display: none;">
            <?php include __DIR__ . '/answered.php' ?>
        </div>

    <?php else : ?>

        <div class="aqi-tab-content" id="my_tab">
            <?php include __DIR__ . '/my_questions.php' ?>
        </div>

        <div class="aqi-tab-content" id="add_tab" style="display: none;">
            <?php include __DIR__ . '/add.php' ?>
        </div>

    <?php endif; ?>

</div>
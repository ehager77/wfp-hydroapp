<?php if (isset($_GET['tab']) && $_GET['tab'] === 'participant') : ?>

    <a href="?">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('New questions') ?>
        </div>
    </a>
    <a href="?tab=answered">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('Answered') ?>
        </div>
    </a>

<?php elseif (isset($_GET['tab']) && $_GET['tab'] === 'answered') : ?>

    <a href="?tab=participant">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('Ongoing issues') ?>
            <?php if ($info['chats']['unread_participant'] > 0) : ?>
                <span class="pnLabel pnLabel_white"><?= $info['chats']['unread_participant'] ?></span>
            <?php endif; ?>
        </div>
    </a>
    <a href="?">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('New questions') ?>
        </div>
    </a>

<?php else : ?>

    <a href="?tab=participant">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('Ongoing issues') ?>
            <?php if ($info['chats']['unread_participant'] > 0) : ?>
                <span class="pnLabel pnLabel_white"><?= $info['chats']['unread_participant'] ?></span>
            <?php endif; ?>
        </div>
    </a>
    <a href="?tab=answered">
        <div class="pnSwitcherHuge__button">
            <?php pll_e('Answered') ?>
        </div>
    </a>

<?php endif; ?>
<?php
/* @var $this yii\web\View */
/* @var $field array */
?>
<span class="csrf" style="display: none"><?= Yii::$app->request->getCsrfToken() ?></span>
<span class="ajax-url" style="display: none"><?= Yii::$app->request->url ?></span>
<div class="row">
    <div>
        <table class="algorithm">
            <?php for ($i = 0; $i < 50; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < 50; $j++): ?>
                        <td class="cell" data-x="<?= $j ?>" data-y="<?= $i ?>"></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
    <div class="info">
        <p>
            <br>
            <span>
                <span style="width: 10px; height: 10px; background: red; display: inline-block;"></span> - A* [
                <span class="time astar"><span class="s"></span><span class="ms"></span><span class="mcs"></span></span>
                ]
            </span>
            <br>
            <span>
                <span style="width: 10px; height: 10px; background: yellow; display: inline-block;"></span> - Li &nbsp[
                <span class="time li"><span class="s"></span><span class="ms"></span><span class="mcs"></span></span>
                ]
            </span>
            <br>
            <span><span style="width: 10px; height: 10px; background: grey; display: inline-block;"></span> - intersect</span>
        </p>
        <br>
        <p>Alt + left click - set start point</p>
        <p>Left click - set end point and find path</p>
        <p>Ctrl + left click - set/unset single obstacle</p>
        <p>Ctrl + Alt + mouse move - draw obstacles</p>
    </div>
</div>

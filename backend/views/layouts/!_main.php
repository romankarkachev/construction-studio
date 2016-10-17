<?php
?>
                <ul class="nav" id="side-menu">
                    <li>
                        <!--<a href="index-2.html"> <span class="nav-label">Dashboard</span> <span class="label label-success pull-right">v.1</span> </a>-->
                        <?= Html::a('<span class="nav-label text-primary">Контрагенты</span> <i class="fa fa-male pull-right text-primary" aria-hidden="true"></i>', ['/counteragents']) ?>

                    </li>
                    <li>
                        <?= Html::a('<span class="nav-label text-info">Объекты</span> <i class="fa fa-building-o pull-right text-info" aria-hidden="true"></i>', ['/facilities']) ?>

                    </li>
                    <li>
                        <?= Html::a('<span class="nav-label text-success">Оплата</span> <i class="fa fa-money pull-right text-success" aria-hidden="true"></i>', ['/payment']) ?>

                    </li>
                    <li>
                        <a href="#"><span class="nav-label">Справочники</span><span class="fa arrow"></span> </a>
                        <ul class="nav nav-second-level">
                            <li><?= Html::a('Номенклатура', ['/ps']) ?></li>
                            <li><?= Html::a('Единицы измерения', ['/units']) ?></li>
                            <li><?= Html::a('Валюта', ['/currencies']) ?></li>
                            <li><?= Html::a('Регионы', ['/regions']) ?></li>
                            <li><?= Html::a('Типы номенклатуры', ['/ps-types']) ?></li>
                            <li><?= Html::a('Типы контрагентов', ['/ca-types']) ?></li>
                            <li><?= Html::a('Способы оплаты', ['/payment-methods']) ?></li>
                            <li><?= Html::a('Статусы объектов', ['/facilities-states']) ?></li>
                        </ul>
                    </li>
                    <li>
                        <?= Html::a('<i class="fa fa-users pull-right" aria-hidden="true"></i> <span class="nav-label">Пользователи</span>', ['/users']) ?>

                    </li>
                </ul>

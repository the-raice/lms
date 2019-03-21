                <form method="post" action="">
                    <h1 class="content__header___title">Регистрация и настройка сайта</h1>
                    <?php if ( !empty( $error ) ): ?>
                    <div class="error"><?=$error ?></div>
                    <?php endif; ?>
                    <label class="form__label" for="server">Название сайта:</label>
                    <br>
                    <input value="<?=$title ?>" required="" class="form__input" type="text" name="title">
                    <br>
                    <label class="form__label" for="server">Имя пользователя:</label>
                    <br>
                    <input value="<?=$username ?>" required="" class="form__input" type="text" name="username">
                    <br>
                    <label class="form__label" for="server">E-mail:</label>
                    <br>
                    <input value="<?=$email ?>" required="" class="form__input" type="email" name="email">
                    <br>
                    <label class="form__label" for="server">Пароль:</label>
                    <br>
                    <input value="" required="" class="form__input" type="password" name="password">
                    <br>
                    <label class="form__label" for="server">Повторите пароль:</label>
                    <br>
                    <input value="" required="" class="form__input" type="password" name="repassword">
                    <br>
                    <br>
                    <input class="form__submit" value="Готово!" type="submit" id="submit">
                </form>

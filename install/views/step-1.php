                <form method="post" action="">
                    <h1 class="content__header___title">Настройка базы данных</h1>
                    <?php if ( !empty( $error ) ): ?>
                    <div class="error"><?=$error ?></div>
                    <?php endif; ?>
                    <label class="form__label" for="server">Сервер базы данных:</label>
                    <br>
                    <input value="<?=$_POST['host'] ?>" required="" class="form__input" type="text" name="host">
                    <br>
                    <label class="form__label" for="server">Имя базы данных:</label>
                    <br>
                    <input value="<?=$_POST['name'] ?>" required="" class="form__input" type="text" name="name">
                    <br>
                    <label class="form__label" for="server">Имя пользователя базы данных:</label>
                    <br>
                    <input value="<?=$_POST['user'] ?>" required="" class="form__input" type="text" name="user">
                    <br>
                    <label class="form__label" for="server">Пароль сервера базы данных:</label>
                    <br>
                    <input class="form__input" type="password" name="password">
                    <br>
                    <br>
                    <input class="form__submit" value="Перейти к третьему шагу&nbsp;&nbsp;&nbsp;>" type="submit" id="submit">
                </form>

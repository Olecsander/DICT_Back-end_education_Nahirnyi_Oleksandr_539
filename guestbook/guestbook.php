<?php
// TODO 1: PREPARING ENVIRONMENT
session_start();

$fileName = "comments.csv";
$errors = [];

// TODO 3: CODE by REQUEST METHODS (ОБРОБКА ФОРМИ)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) Перевірка наявності даних (isset/empty)
    if (empty($_POST["email"]) || empty($_POST["name"]) || empty($_POST["text"])) {
        $errors[] = "Заповніть всі поля!";
    } else {
        $email = trim($_POST["email"]);
        $name = trim($_POST["name"]);
        $text = trim($_POST["text"]);

        // 2) Валідація
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Невірний формат email!";
        }

        if (empty($errors)) {
            // 3) Підготовка та збереження в CSV
            $file = fopen($fileName, "a");
            // fputcsv записує масив у файл, розділяючи елементи комами
            fputcsv($file, [$email, $name, $text, date("Y-m-d H:i:s")]);
            fclose($file);

            // Редирект, щоб уникнути дублювання при оновленні сторінки (F5)
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// TODO: render guestBook comments (ОТРИМАННЯ ДАНИХ)
$comments = [];
if (file_exists($fileName)) {
    $file = fopen($fileName, "r");
    // fgetcsv читає рядок і автоматично розбиває його в масив
    while (($row = fgetcsv($file)) !== FALSE) {
        $comments[] = [
            'email' => $row[0],
            'name'  => $row[1],
            'text'  => $row[2],
            'date'  => $row[3]
        ];
    }
    fclose($file);
    // Відображаємо останні коментарі зверху
    $comments = array_reverse($comments);
}
?>

<!DOCTYPE html>
<html>
<?php require_once 'sectionHead.php' ?>
<body>
<div class="container">
    <?php require_once 'sectionNavbar.php' ?>
    <br>

    <!-- GuestBook form -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">GuestBook form</div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <!-- HTML FORM -->
                    <form method="POST">
                        <div class="mb-2">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="text" class="form-control" placeholder="Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger mt-2">
                            <?php foreach ($errors as $e): ?>
                                <div><?= htmlspecialchars($e) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Comments section -->
    <div class="card card-primary">
        <div class="card-header bg-body-secondary text-dark">Comments</div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <?php if (empty($comments)): ?>
                        <p>Коментарів поки немає...</p>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <div class="border-bottom mb-3 pb-2">
                                <strong><?= htmlspecialchars($c['name']) ?></strong>
                                <small class="text-muted">(<?= htmlspecialchars($c['email']) ?>)</small>
                                <div class="mt-1"><?php echo nl2br(htmlspecialchars($c['text'])); ?></div>
                                <small class="text-secondary" style="font-size: 0.8rem;"><?= $c['date'] ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

  <?php
    require_once('functions.php');

    $pdo = connectDB();

    // var_dump($_FILES['image']);
    // exit();
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        // 画像を取得
        // var_dump('画像を取得');
        $sql = 'SELECT * FROM images ORDER BY created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $images = $stmt->fetchAll();
    } else {
        // 画像を保存

        if (!empty($_FILES['image']['name'])) {
            // var_dump('OK');
            $name = $_FILES['image']['name'];
            $type = $_FILES['image']['type'];
            $content = file_get_contents($_FILES['image']['tmp_name']);
            $size = $_FILES['image']['size'];

            $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at)
VALUES (:image_name, :image_type, :image_content, :image_size, now())';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
            $stmt->execute();
        }
        // var_dump('ng');
        header('Location:list.php');
        exit();
    }

    ?>

  <!DOCTYPE html>
  <html lang="ja">

  <head>
      <meta charset="utf-8">
      <title>Image Test</title>
      <!-- BootstrapのCSS読み込み -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
      <script src="https://kit.fontawesome.com/2b550a7e38.js" crossorigin="anonymous"></script>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  </head>

  <body>
      <a href="list2.php">6月22日リストページへ</a>
      <div class="container mt-5">
          <div class="row">
              <h1>購入item</h1>
              <div class="col-md-8 border-right">
                  <ul class="list-unstyled">
                      <?php for ($i = 0; $i < count($images); $i++) : ?>
                          <li class="media mt-5">
                              <a href="#lightbox" data-toggle="modal" data-slide-to="<?= $i; ?>">
                                  <img src="image.php?id=<?= $images[$i]['image_id']; ?>" width="100px" height="auto" class="mr-3">
                              </a>
                              <div class="media-body">
                                  <h5><?= $images[$i]['image_name']; ?> (<?= number_format($images[$i]['image_size'] / 1000, 2); ?> KB)</h5>
                                  <a href="#"><i class="far fa-trash-alt"></i> 削除</a>
                              </div>
                          </li>
                      <?php endfor; ?>
                  </ul>
              </div>
              <div class="col-md-4 pt-4 pl-4">

                  <form method="post" action='create.php' enctype="multipart/form-data">
                      <div class="form-group">
                          <label>画像を選択</label>
                          <input type="file" name="image">
                      </div>
                      <button type="submit" class="btn btn-primary">保存</button>
                  </form>
                  <style>
                      html {
                          font-family: sans-serif;
                      }

                      h1 {
                          text-align: center;
                          background-color: #5989cf;
                      }

                      .form-group {
                          padding: 8px 19px;
                          margin: 2em 0;
                          color: #2c2c2f;
                          background: #cde4ff;
                          border-top: solid 5px #5989cf;
                          border-bottom: solid 5px #5989cf;
                      }

                      .form-group p {
                          margin: 0;
                          padding: 0;
                      }

                      .btn {
                          margin-left: 150px;
                      }
                  </style>
              </div>
          </div>
      </div>

      <div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-body">
                      <ol class="carousel-indicators">
                          <?php for ($i = 0; $i < count($images); $i++) : ?>
                              <li data-target="#lightbox" data-slide-to="<?= $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
                          <?php endfor; ?>
                      </ol>

                      <div class="carousel-inner">
                          <?php for ($i = 0; $i < count($images); $i++) : ?>
                              <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>">
                                  <img src="image.php?id=<?= $images[$i]['image_id']; ?>" class="d-block w-100">
                              </div>
                          <?php endfor; ?>
                      </div>

                      <a class="carousel-control-prev" href="#lightbox" role="button" data-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#lightbox" role="button" data-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                      </a>
                  </div>
              </div>
          </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>

  </html>
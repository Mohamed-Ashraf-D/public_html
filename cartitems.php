<?php
ob_start();
session_start();
$pageTitle = 'Cart Items';
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['uid'];
    include "init.php";
    ?>
    <div class="container">
        <div class="container">
            <div class="row">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>QUANTITY</th>
                        <th>UNIT PRICE</th>
                        <th>SUBTOTAL</th>
                        <th>CONTROL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr hidden>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input class="form-control pinput pSubTotal"
                                   disabled type="number" min="1" step="1" value="0"></td>
                    </tr>
                    <?php
                    $stmt = $conn->prepare('SELECT * FROM cartitems join items ON cartitems.item_id=items.item_ID WHERE user_id=? ORDER BY id DESC');
                    $stmt->execute(array($userId));
                    $cartItems = $stmt->fetchAll();
                    foreach ($cartItems as $cartItem) {
                        ?>

                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-2">
                                        <!--                               <img height="70" alt="product image" src="layout/img/placeholder-profile-sq.jpg">-->
                                        <?php
                                        if (empty($cartItem['Image'])) {
                                            echo "<img alt='product-image' height='70' class='card-img-top' src='layout/img/placeholder-profile-sq.jpg'>";
                                        } else {
                                            echo "<img alt='product-image' height='70'  class='card-img-top' src='layout/img/{$cartItem['Image']}'>";
                                        }
                                        ?>
                                    </div>
                                    <div class="col-10">
                                        <h6 class="product-name"><?= $cartItem['Name'] ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td class="qtd">
                                <input class="form-control qinput productQ" data-id2="<?=$cartItem['item_id']?>" id="q<?= $cartItem['id'] ?>"
                                       data-id="q<?= $cartItem['id'] ?>" name="productQ" type="number" min="1" step="1"
                                       value="<?= $cartItem['product_q'] ?>">
                                <span class="error text-danger"></span>
                            </td>
                            <td class="ptd">
                                <input class="form-control pinput pUnitP" id="p<?= $cartItem['id'] ?>" name="uPrice"
                                       disabled type="number" min="1" step="1" value="<?= $cartItem['price'] ?>">
                            </td>
                            <td class="ptd">
                                <?php
                                $subTotal = $cartItem['product_q'] * $cartItem['price'];
                                ?>
                                <input class="form-control pinput pSubTotal" id="t<?= $cartItem['id'] ?>"
                                       disabled type="number" min="1" step="1" value="<?= $subTotal ?>">
                            </td>
                            <td class="ptd">
                                <button class="btn btn-danger btn-sm remove" type="button"><i
                                            class="fa fa-trash"></i> Remove
                                </button>
                                <input type="hidden" id="idCartItem<?= $cartItem['id'] ?>" value="<?= $cartItem['id'] ?>">
                            </td>
                        </tr>
                        
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div class="offset-3 col-2">
                    <input href="paymentfrm.php" class="btn btn-primary mt-4"  type="submit" value="Submit Purchase" name="purchase"
                           id="purchase">
                </div>
                <div class="offset-4 col-2">
                    <label class="font-weight-bold">TOTAL</label>
                    <input class="form-control" type="text" disabled name="total" id="total">
                </div>


            </div>
            <br><br><br>
        </div>


    </div>

    <?php include $tpl . 'footer.php';
} ?>


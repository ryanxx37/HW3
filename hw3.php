<?php
include 'tic-tac-toe-functions.php';

session_start();


if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); 
    $_SESSION['turn'] = 'X';
}

if (isset($_POST['reset'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['turn'] = 'X';
    unset($_SESSION['winner']);
}


if (isset($_POST['cell']) && !isset($_SESSION['winner'])) {
    $cell = (int) $_POST['cell'];


    if ($_SESSION['board'][$cell] == '') {
        $_SESSION['board'][$cell] = $_SESSION['turn'];
        updateSessionBoard(); 


        $winner = whoIsWinner();
        if ($winner != null) {
            $_SESSION['winner'] = $winner;
        } elseif (!in_array('', $_SESSION['board'])) {
            $_SESSION['winner'] = 'Draw';
        }


        if (!isset($_SESSION['winner'])) {
            $_SESSION['turn'] = $_SESSION['turn'] == 'X' ? 'O' : 'X';
        }
    }
}


function updateSessionBoard() {
    for ($i = 0; $i < 9; $i++) {
        $col = ($i % 3) + 1; 
        $row = floor($i / 3) + 1;
        $key = "$col-$row";
        $_SESSION[$key] = $_SESSION['board'][$i];
    }
}

function message() {
    if (isset($_SESSION['winner']) && $_SESSION['winner'] != '') {
        echo '<h2>' . $_SESSION['winner'] == 'Draw' ? 'It\'s a Draw!' : 'The winner is ' . $_SESSION['winner'] . '!!</h2>';
    } else {
        echo '<h2>Turn: ' . $_SESSION['turn'] . '</h2>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tic Tac Toe</title>
</head>
<body>

    <h1>Tic Tac Toe</h1>

    <div><?php message() ?></div>

    <table>
        <?php for ($row = 0; $row < 3; $row++): ?>
            <tr>
                <?php for ($col = 0; $col < 3; $col++): 
                    $cellIndex = $row * 3 + $col; ?>
                    <td>
                        <?php if ($_SESSION['board'][$cellIndex] == ''): ?>
                            <?php if (!isset($_SESSION['winner']) || $_SESSION['winner'] == ''): ?>
                                <form method="post">
                                    <input type="hidden" name="cell" value="<?php echo $cellIndex; ?>">
                                    <button type="submit"><?php echo $_SESSION['turn']; ?></button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <span><?php echo $_SESSION['board'][$cellIndex]; ?></span>
                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>


    <form method="post">
        <button type="submit" name="reset">Reset Game</button>
    </form>

</body>
</html>

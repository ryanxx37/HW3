<?php
include 'tic-tac-toe-functions.php';

session_start();


if (!isset($_SESSION['board'])) {
    fillX();
}

if (isset($_POST['reset'])) {
    fillX();
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

function fillX() {
    $_SESSION['board'] = array_fill(0, 9, ''); 
    $_SESSION['turn'] = 'X';
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
        echo '<h2>';
        echo $_SESSION['winner'] == "Draw" ? "It\'s a Draw!" : "The winner is " . $_SESSION['winner'] . "!!";
        echo '</h2>';
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
    <style>
		/* Button background is blue with a black border*/
		button {
			background-color: #3498db;
			height: 100%;
			width: 100%;
			text-align: center;
			font-size: 20px;
			color: white;
			vertical-align: middle;
			border: 0px;
		}

		/* Styles the table cells to look like a tic-tac-toe grid */
		table td {
			text-align: center;
			vertical-align: middle;
			padding: 0px;
			margin: 0px;
			width: 75px;
			height: 75px;
			font-size: 20px;
			border: 3px solid #040404;
			color: white;
		}

		/* This shows a darker blue background when the mouse hovers over the buttons */
		button:hover,
		input[type="submit"]:hover,
		button:focus,
		input[type="submit"]:focus {
			background-color: #04469d;
			text-decoration: none;
			outline: none;
		}
    </style>
</head>
<body>

    <h1>Tic Tac Toe</h1>

    <?php message() ?>

    <table>
        <?php for ($row = 0; $row < 3; $row++): ?>
            <tr>
                <?php for ($col = 0; $col < 3; $col++): 
                    $cellIndex = $row * 3 + $col; ?>
                    <td style="background-color: 
                    <?php echo $_SESSION['board'][$cellIndex] == 'X' ? 'green' : ($_SESSION['board'][$cellIndex] == 'O' ? 'red' : ''); ?>; color: black;">
                        <?php if ($_SESSION['board'][$cellIndex] == ''): ?>
                            <?php if (!isset($_SESSION['winner']) || $_SESSION['winner'] == ''): ?>
                                <form method="post" style="height: 100%; width: 100%; padding: 0; margin: 0;">
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

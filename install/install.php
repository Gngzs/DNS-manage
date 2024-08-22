<?php
header('Content-Type: application/json');

if (isset($_POST['action'])) {
    if (!file_exists('install.lock')) {
        switch ($_POST['action']) {
            case 'checkPHP':
                $results = [];
                // 检测 PHP 版本
                $phpVersion = phpversion();
                $phpVersionCheck = version_compare($phpVersion, '8.1', '>');
                $results[] = [
                    'id' => 1,
                    'name' => 'php>8.1',
                    'result' => $phpVersionCheck
                ];

                // 检测扩展
                $extensionsToCheck = ['zip', 'openssl', 'gd', 'curl', 'mysqli'];

                foreach ($extensionsToCheck as $index => $extension) {
                    $results[] = [
                        'id' => $index + 2, // 从2开始
                        'name' => $extension,
                        'result' => extension_loaded($extension)
                    ];
                }
                $message = $results;
                break;
            case 'checkDatabase':
                $message = checkDatabaseConnection($_POST['host'], $_POST['user'], $_POST['password'], $_POST['dbname']);
                break;
            case 'importSql':
                $message = importSqlAndWriteConfig($_POST['host'], $_POST['user'], $_POST['password'], $_POST['dbname'], 'example.sql', '../common/config2.php', $_POST['key']);
                break;
        }
        echo json_encode($message);
    } else {
        echo json_encode(['success' => false, 'message' => '程序已安装，如需覆盖安装请删除install.lock']);
    }
}

function checkDatabaseConnection($host, $username, $password, $dbname)
{
    try {
        // 尝试创建连接
        $connection = new mysqli($host, $username, $password, $dbname);

        // 检测连接是否成功
        if ($connection->connect_error) {
            throw new Exception("连接错误: " . $connection->connect_error);
        }

        // 如果连接成功，关闭连接
        $connection->close();
        return [
            'success' => true
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

//$result = checkDatabaseConnection('localhost:3306', 'root', 'love1314%', 'dns');
//echo json_encode($result);
/*
if ($result['success']) {
    echo "数据库连接成功!";
} else {
    echo "数据库连接失败: " . $result;
}
*/

function importSqlAndWriteConfig($host, $username, $password, $dbname, $sqlFilePath, $configFilePath, $key)
{
    // 开启异常处理
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // 创建数据库连接
        $connection = new mysqli($host, $username, $password, $dbname);

        //清空数据库
        $tables = $connection->query("SHOW TABLES");
        while ($row = $tables->fetch_array()) {
            $tableName = $row[0];
            $connection->query("DROP TABLE IF EXISTS `$tableName`");
        }

        // 读取 SQL 文件
        $sql = file_get_contents($sqlFilePath);
        if ($sql === false) {
            throw new Exception("读取 SQL 文件失败");
        }

        // 执行 SQL 语句
        if ($connection->multi_query($sql)) {
            do {
                // 如果有结果集，获取并丢弃
                if ($result = $connection->store_result()) {
                    $result->free();
                }
            } while ($connection->more_results() && $connection->next_result());
        }

        // 关闭连接
        $connection->close();

        // 写入配置文件
        $configContent = "<?php\n";
        $configContent .= "\$sqlhost = '$host';\n";
        $configContent .= "\$sqluser = '$username';\n";
        $configContent .= "\$sqlpassword = '$password';\n";
        $configContent .= "\$sqlname = '$dbname';\n";
        $configContent .= "\$key = '$key';\n";

        if (file_put_contents($configFilePath, $configContent) === false) {
            throw new Exception("写入配置文件失败");
        }
        $lock = file_put_contents("install.lock", "本程序已安装，如需覆盖安装请删除本文件");
        if ($lock === false) {
            throw new Exception("数据库已导入，install.lock文件创建失败，确保安全请手动删除install文件夹");
        }
        return ["success" => true, "message" => "安装成功"];
    } catch (Exception $e) {
        return ["success" => false, "message" => "安装失败: " . $e->getMessage()];
    }
}

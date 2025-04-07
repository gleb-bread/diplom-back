const mysql = require('mysql2');
const axios = require('axios'); // Можно использовать для отправки HTTP-запросов

// Подключение к MySQL
const dbConnection = mysql.createConnection({
    host: '127.0.0.1',        // Хост базы данных
    user: 'gleb',     // Имя пользователя базы данных
    password: 'g0f9d8s7A', // Пароль пользователя
    database: 'diplom',    // Имя базы данных
    port: 8889,
});

// Подключение и обработка ошибок
dbConnection.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
        return;
    }
    console.log('Connected to MySQL');
    watchForNewRequests();
});

// Функция для наблюдения за новыми запросами в базе данных
function watchForNewRequests() {
    let lastCheckedId = 0; // Храним ID последней обработанной записи
    setInterval(() => {
        dbConnection.query('SELECT * FROM api_requests WHERE id > ? AND status = "pending" ORDER BY created_at ASC LIMIT 1', [lastCheckedId], (err, results) => {
            if (err) {
                console.error('Error fetching queue data:', err);
                return;
            }

            if (results.length > 0) {
                const apiRequest = results[0]; // Получаем первый запрос из очереди
                const { id, url, method, headers, cookies, params } = apiRequest;

                // Обработаем запрос
                handleRequest(apiRequest);

                // Обновим статус запроса на "in-progress"
                dbConnection.query('UPDATE api_requests SET status = "in-progress" WHERE id = ?', [id], (err, res) => {
                    if (err) {
                        console.error('Error updating status:', err);
                        return;
                    }
                    console.log(`Request ${id} is now in-progress`);
                });

                // Обновляем lastCheckedId на id текущей записи
                lastCheckedId = id;
            } else {
                console.log('No new pending requests');
            }
        });
    }, 1000);  // Интервал 1 секунда для отслеживания новых записей
}

// Функция для обработки запроса
function handleRequest(apiRequest) {
    const { id, url, method, headers, cookies, params } = apiRequest;

    const config = {
        method: method.toLowerCase(),  // Преобразуем метод в нижний регистр
        url: url,
        headers: headers ? headers : {},
        params: params ? params : {},
        timeout: 10000,
        withCredentials: cookies ? true : false,
        data: method.toLowerCase() === 'post' ? (params ? params : {}) : undefined,
    };

    // Отправка запроса через axios
    axios(config)
        .then((response) => {
            console.log(`Request to ${url} completed successfully`);

            // Обновление статуса и ответа в базе данных
            updateRequestStatus(id, 'completed', response.data);
        })
        .catch((error) => {
            console.error(`Error processing request to ${url}:`, error);
            updateRequestStatus(id, 'failed', error);
        });
}

// Функция для обновления статуса запроса в базе данных
function updateRequestStatus(id, status, response) {
    const responseData = typeof response === 'object' ? JSON.stringify(response) : response;
    dbConnection.query(
        'UPDATE api_requests SET status = ?, response = ? WHERE id = ?',
        [status, responseData, id],
        (err, res) => {
            if (err) {
                console.error('Error updating status in database:', err);
                return;
            }
            console.log(`Request ${id} status updated to "${status}"`);
        }
    );
}

// Закрытие соединения с MySQL
process.on('SIGINT', () => {
    dbConnection.end((err) => {
        if (err) {
            console.error('Error ending MySQL connection:', err);
        } else {
            console.log('MySQL connection closed');
        }
        process.exit(0);
    });
});

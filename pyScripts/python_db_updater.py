import MySQLdb
import pymssql
import time






def connect_to_mssql():
    DB_CONNECTION = {
    'server': "10.22.10.139\\SQLSERVER2008",
    'user': "sa",
    'password': "123456",
    'database': "Orion"
}
    cnxn = pymssql.connect(**DB_CONNECTION)
    cursorMS = cnxn.cursor()
    return cursorMS


def connect_to_ubuntu_sql(SQL):
    try:
        db = MySQLdb.connect(host="100.100.100.109", user="tex", passwd="Te99874566", db = "timesheet")
        cursor = db.cursor()
        cursor.execute(SQL)
        db.commit()
        data = cursor.fetchone()
        db.close()

        return data


    except:
        print("Ошибка подключения к БД убунту")

def check_last_update_date():
    SQL = "SELECT MAX(update_date) FROM update_log;"
    last_update_date = connect_to_ubuntu_sql(SQL)
    
    
    new = str(last_update_date[0]).split()
    year = new[0].split('-')[0]
    month = new[0].split('-')[1]
    day = new[0].split('-')[2]
    hour = new[1].split(':')[0]
    minute = new[1].split(':')[1]
    second = new[1].split(':')[2]

    last_update_date = f'{year}.{month}.{day} {hour}:{minute}:{second}'
    
    return last_update_date

def check_new_update(last_update_date):
    time_now = time.strftime("%Y-%m-%d %H:%M:%S")
    print(f"Сейчас {time_now}")
    SQL_ACCESS = f"""SELECT HozOrgan as worker_id, TimeVal as occur_at, Par4 as code
        FROM pLogData
        WHERE pLogData.TimeVal > '{last_update_date}' AND pLogData.TimeVal <= '{time_now}' AND pLogData.Event = 32
        ORDER BY TimeVal;
        """

    cursorMS = connect_to_mssql()
    cursorMS.execute(SQL_ACCESS)
    output = cursorMS.fetchall()
    return output

def updating(update):
    print(f'Всего {len(update)} новых записи, начинается обновление...')
    f = open('text.txt', 'a')
    for i in update:
            
        worker_id = i[0]
        datetime = i[1]
        event = i[2]
        print(str(worker_id) + ' ' + str(datetime))
        SQL = f"INSERT INTO timeing (user_id, datetime, event) VALUES ('{worker_id}', '{datetime}', '{event}')"
        connect_to_ubuntu_sql(SQL)
    
def update_log(new_date):
    SQL = f"INSERT INTO update_log (update_date, status) VALUES ('{new_date}', '1')"
    connect_to_ubuntu_sql(SQL)
    pass


def main():
    while True:
        try:
            last_update_date = check_last_update_date()
            
            print(f'Последняя дата обновления {last_update_date}')
        except:
            print('Ошибка проверки последнего обновления')
            return

        try:
            update = check_new_update(last_update_date)
            if update != []:
                print(f'Доступно для обновления {len(update)} новых записи')
                print(update[len(update) - 1][1])
                try:
                    updating(update)
                except:
                    print('Ошибка загрузки обновлений')
                    return
                    
                new_update_date = update[len(update) - 1][1]
                update_log(new_update_date)
                print('Обновление успешно выполнено!')


            else:
                print('Новых записей нет')
        
        except:
            print('Ошибка')
        time.sleep(60)



if __name__ == '__main__':
    main()

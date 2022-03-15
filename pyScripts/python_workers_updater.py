from mysql.connector import connect, Error
import pyodbc
import MySQLdb
import pymssql
import time


time_now = time.strftime("%d.%m.%Y")




def connect_to_mssql():
    cnxn = pyodbc.connect('DRIVER={ODBC Driver 17 for SQL Server};SERVER=10.22.10.139\\SQLSERVER2008;DATABASE=Orion;UID=sa;PWD=123456')
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

SQL_WORKERS = f"""SELECT pList.ID as worker_id, pList.Name as last_name, FirstName as first_name, MidName as middle_name,
  PCompany.Name as company, PDivision.Name as division, PPost.Name as post, pList.DateTimeInArchive as date_end
FROM pList
LEFT JOIN PCompany ON pList.Company = PCompany.ID
LEFT JOIN PDivision ON pList.Section = PDivision.ID
LEFT JOIN PPost ON pList.Post = PPost.ID
WHERE pList.ID IN (
  SELECT DISTINCT pLogData.HozOrgan
  FROM pLogData
  WHERE pLogData.TimeVal > '01.01.2020' AND pLogData.TimeVal <= '{time_now}' AND pLogData.Event = 32
)
ORDER BY pList.Name, FirstName, MidName;
"""
bad_users = [1]

def main():
    cursorMS = connect_to_mssql()
    cursorMS.execute(SQL_WORKERS)
    data = cursorMS.fetchall()
    connect_to_ubuntu_sql('TRUNCATE workers;')
    for i in data:
        
        if i[0] not in bad_users:
            print(i[1])
            if i[7] == data[0][7]:
                SQL = f"INSERT INTO workers VALUES ('{i[0]}', '{i[1]}', '{i[2]}', '{i[3]}', '{i[4]}', '{i[5]}', '{i[6]}', null);"
                connect_to_ubuntu_sql (SQL)
            else:
                connect_to_ubuntu_sql (f"INSERT INTO workers VALUES ('{i[0]}', '{i[1]}', '{i[2]}', '{i[3]}', '{i[4]}', '{i[5]}', '{i[6]}', '{i[7]}');")
        else:
            print(f"{i[0]} {i[2]} пользователь не добавлен")





if __name__ == "__main__":
    main()
from mysql.connector import connect, Error
import pyodbc
import MySQLdb
import pymssql







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

SQL_WORKERS = """SELECT pList.ID as worker_id, pList.Name as last_name, FirstName as first_name, MidName as middle_name,
  PCompany.Name as company, PDivision.Name as division, PPost.Name as post
FROM pList
LEFT JOIN PCompany ON pList.Company = PCompany.ID
LEFT JOIN PDivision ON pList.Section = PDivision.ID
LEFT JOIN PPost ON pList.Post = PPost.ID
WHERE pList.ID IN (
  SELECT DISTINCT pLogData.HozOrgan
  FROM pLogData
  WHERE pLogData.TimeVal > '01.01.2021' AND pLogData.TimeVal <= '09.03.2022' AND pLogData.Event = 32
)
ORDER BY pList.Name, FirstName, MidName;
"""


def main():
    cursorMS = connect_to_mssql()
    cursorMS.execute(SQL_WORKERS)
    data = cursorMS.fetchall()
    connect_to_ubuntu_sql('TRUNCATE workers;')
    for i in data:
        connect_to_ubuntu_sql (f"INSERT INTO workers VALUES ('{i[0]}', '{i[1]}', '{i[2]}', '{i[3]}', '{i[4]}', '{i[5]}', '{i[6]}');")
        print(i[1])

    pass



if __name__ == "__main__":
    main()
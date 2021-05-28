# coding: utf-8
# import tabula
# import openpyxl
# import datetime

# print(__name__)
# # csvファイルに変換
# # tabula.convert_into("/uploads/練習pdf/2021_Apl._menu.pdf","/uploads/練習メニュー.csv",stream=True  , output_format="csv")

# # excelファイルに変換
# dfs = tabula.read_pdf("/uploads/練習pdf/2021_May_短距離系.pdf", pages='all', lattice=True)
# dfs.to_excel("/uploads/練習メニュー.xlsx")

# # excelの操作

# wb = openpyxl.load_workbook("/uploads/練習メニュー.xlsx")
# ws = wb["Sheet1"]
# now_date = datetime.datetime.now()

# menus = ws["P3:P32"]
# # for m in menus:
# #     print(m[0].value)
# print(now_date)
# print(menus[now_date.day-1][0].value)
print('from python')
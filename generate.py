import mysql.connector
import random
from datetime import datetime, timedelta

# Database configuration
db_config = {
    'host': 'localhost',          # Change this to your MySQL host
    'user': 'root',      # Change this to your MySQL username
    'password': '',  # Change this to your MySQL password
    'database': 'posystem'   # Change this to your MySQL database name
}

# Create a database connection
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Function to generate a random date between two dates
def random_date(start, end):
    return start + timedelta(days=random.randint(0, (end - start).days))

# Function to generate random sales data and insert it into the database
def generate_sales_data(num_entries):
    for _ in range(num_entries):
        code = random.randint(10001, 99999)
        
        # Generate random netPrice and totalPrice as whole numbers with a minimum of 200
        net_price = random.randint(200, 1000)  # Random net price between $200 and $1000
        total_price = net_price  # For this case, total price equals net price
        
        tax = 0  # Always zero tax
        payment_method = 'cash'
        sale_date = random_date(datetime(2019, 1, 1), datetime.now()).strftime('%Y-%m-%d %H:%M:%S')

        # Insert data into the sales table
        cursor.execute(
            """
            INSERT INTO sales (code, products, addons, tax, netPrice, totalPrice, paymentMethod, saledate)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """,
            (code, '', '', tax, net_price, total_price, payment_method, sale_date)
        )

# Generate and insert 100 random sales records
generate_sales_data(100)

# Commit the transaction
conn.commit()

# Close the database connection
cursor.close()
conn.close()

print("Sales data generated and inserted successfully.")

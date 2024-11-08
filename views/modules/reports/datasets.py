import pandas as pd
import mysql.connector
import json

# Database connection configuration
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'posystem'
}

def fetch_sales_data():
    conn = mysql.connector.connect(**db_config)
    query = "SELECT DATE(saledate) AS sale_date, SUM(totalPrice) AS total_sales FROM sales GROUP BY sale_date ORDER BY sale_date"
    df = pd.read_sql(query, conn)
    conn.close()
    return df

def prepare_data(df):
    df['sale_date'] = pd.to_datetime(df['sale_date'])
    df['day'] = df['sale_date'].dt.day
    df['month'] = df['sale_date'].dt.month
    df['year'] = df['sale_date'].dt.year
    df['weekday'] = df['sale_date'].dt.weekday

    X = df[['day', 'month', 'year', 'weekday']]
    y = df['total_sales']
    return X, y, df['sale_date']

def predict_sales(X, y, last_sale_date, future_days=30):
    from sklearn.model_selection import train_test_split
    from sklearn.linear_model import LinearRegression

    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    model = LinearRegression()
    model.fit(X_train, y_train)

    future_dates = pd.date_range(start=last_sale_date.max() + pd.Timedelta(days=1), periods=future_days)
    future_data = pd.DataFrame({
        'day': future_dates.day,
        'month': future_dates.month,
        'year': future_dates.year,
        'weekday': future_dates.weekday
    })

    predictions = model.predict(future_data)

    # Prepare the structured output
    result = []
    for i, year in enumerate(future_data['year']):
        result.append({
            'y': year,
            'Sales': predictions[i],
            'type': 'Predicted Sales'
        })

    return result

sales_data = fetch_sales_data()
X, y, last_sale_date = prepare_data(sales_data)
predicted_sales = predict_sales(X, y, last_sale_date)

# Output JSON
try:
    print(json.dumps(predicted_sales, indent=4))
except Exception as e:
    print(f"JSON serialization error: {str(e)}")

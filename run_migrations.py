#!/usr/bin/env python3
"""
Execute database migrations from database.sql against Supabase
"""
import os
import sys
import psycopg2
from dotenv import load_dotenv

# Load environment variables from .env
load_dotenv(os.path.join(os.path.dirname(__file__), '.env'))

def get_db_connection():
    """Create a PostgreSQL connection using DATABASE_URL from .env"""
    db_url = os.getenv('DATABASE_URL')
    if not db_url:
        print("Error: DATABASE_URL not set in .env")
        sys.exit(1)
    
    try:
        conn = psycopg2.connect(db_url)
        return conn
    except psycopg2.Error as e:
        print(f"Error connecting to database: {e}")
        sys.exit(1)

def read_sql_file(filename):
    """Read SQL file and return content"""
    try:
        with open(filename, 'r') as f:
            return f.read()
    except FileNotFoundError:
        print(f"Error: {filename} not found")
        sys.exit(1)

def execute_migrations(sql_content):
    """Execute SQL migrations"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    try:
        # Split on semicolons and execute each statement
        statements = [stmt.strip() for stmt in sql_content.split(';') if stmt.strip()]
        
        for i, statement in enumerate(statements, 1):
            try:
                print(f"Executing statement {i}/{len(statements)}...", end=" ")
                cursor.execute(statement)
                conn.commit()
                print("✓ OK")
            except psycopg2.Error as e:
                print(f"✗ Error")
                print(f"  Statement: {statement[:60]}...")
                print(f"  Error: {e}")
                conn.rollback()
                raise
        
        print("\n✓ All migrations completed successfully!")
        
    except Exception as e:
        print(f"\n✗ Migration failed: {e}")
        sys.exit(1)
    finally:
        cursor.close()
        conn.close()

if __name__ == '__main__':
    print("Running database migrations...")
    sql_content = read_sql_file('database.sql')
    execute_migrations(sql_content)

from cassandra.cluster import Cluster
from cassandra.auth import PlainTextAuthProvider
from uuid import uuid4
import os

scylla = os.getenv("SCYLLA");

# Connect to the cluster (replace '127.0.0.1' with your actual node IP address)
cluster = Cluster([scylla])
session = cluster.connect()

# Create keyspace
session.execute("""
    CREATE KEYSPACE IF NOT EXISTS my_keyspace
    WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 3}
""")

# Create table
session.execute("""
    CREATE TABLE IF NOT EXISTS my_keyspace.users (
        user_id UUID PRIMARY KEY,
        username TEXT,
        email TEXT,
        age INT
    )
""")

# Insert record with TTL
insert_query = """
    INSERT INTO my_keyspace.users (user_id, username, email, age)
    VALUES (%s, %s, %s, %s)
    USING TTL 3
"""
user_id = uuid4()
username = 'john_doe'
email = 'john.doe@example.com'
age = 30

session.execute(insert_query, (user_id, username, email, age))


# add sex col to the table
# insert_query = """
#     ALTER TABLE my_keyspace.users
#     ADD sex TEXT;
# """

# session.execute(insert_query)

# Insert record with TTL
insert_query = """
    INSERT INTO my_keyspace.users (user_id, username, email, age, sex)
    VALUES (%s, %s, %s, %s, %s)
    USING TTL 300
"""
user_id = uuid4()
username = 'john_doe'
email = 'john.doe@example.com'
age = 30
sex = 'M'
session.execute(insert_query, (user_id, username, email, age, sex))

# add sex col to the table
insert_query = """
    ALTER TABLE my_keyspace.users
    DROP sex;
"""

session.execute(insert_query)


# Close connection
cluster.shutdown()

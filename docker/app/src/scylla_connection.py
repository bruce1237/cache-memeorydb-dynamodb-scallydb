from cassandra.cluster import Cluster
import os

scylla = os.getenv("SCYLLA");

# Connect to the cluster (replace '127.0.0.1' with your actual node IP address)
cluster = Cluster([scylla])

# Create a session
session = cluster.connect()





# Use the session to execute queries
session.execute("USE my_keyspace")
rows = session.execute("SELECT * FROM users")

# Process the results
for row in rows:
    print(row)

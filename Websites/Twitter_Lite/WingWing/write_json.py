from neo4j.v1 import GraphDatabase, basic_auth

driver = GraphDatabase.driver("bolt://hobby-fbencgjedgejgbkecbebpccl.dbs.graphenedb.com:24787",
 auth=basic_auth("dbuser", "b.nAnYevLeizNP.AddgjBGB2P7eWPuv"))

group_value = 1

def get_all_users():
    session = driver.session()
    cypher = "MATCH (n:User) RETURN n"
    result = session.run(cypher)
    return [serialize_user(record['n']) for record in result]

def get_followers(username):
    """
    Returns the followers of a user
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:FOLLOWS]->(t) WHERE t.username='" + username + "' RETURN n"
    result = session.run(cypher)
    session.close()
    return [serialize_user(record['n']) for record in result]

def serialize_user(user):
    return {
        'username': user['username'],
        'password': user['password'],
        'bio': user['bio'],
        'photo': user['photo']
    }

def get_num_of_likes(username):
    """
    Returns the number of likes that a user's tweets have
    """
    session = driver.session()
    cypher = "MATCH (:User)-[:LIKED]->(:Tweet)<-[:TWEETED]-(:User {username:'"
    cypher = cypher + username + "'}) RETURN COUNT(*) AS likes"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def create_json_file():
    # clear the file
    f = open("static/miserables.json", "w+").close()
    f = open("static/miserables.json", "w+")
    f.write("{\n")
    f.write('  "nodes":[\n')
    # Users to write the users json part
    all_users = get_all_users()
    total_users = len(all_users)
    for u in range(len(all_users)):
        if u < (total_users - 1):
            f.write('    {"id": "' + str(all_users[u]['username']) + '" ,"likes":"' + str(get_num_of_likes(str(all_users[u]['username']))['likes']) + '", "group": ' + str(get_group_value()) + ' },\n')
        else:
            f.write('    {"id": "' + str(all_users[u]['username']) + '" ,"likes":"' + str(get_num_of_likes(str(all_users[u]['username']))['likes']) + '", "group": ' + str(get_group_value()) + ' }\n')

    f.write("  ],\n")
    f.write('  "links":[\n')
    i = -1
    for u in range(len(all_users)):
        followers_list = get_followers(str(all_users[u]['username']))
        num_of_followers = len(followers_list)
        for foll in range(len(followers_list)):
                i = i + 1
                if i != 0:
                    f.write(",\n")
                f.write('    {"source": "' + str(followers_list[foll]['username']) + '", "label": "following", "target": "' + str(all_users[u]['username']) + '", "value": ' + str(get_group_value()) + ' }')

    f.write("\n")
    f.write("  ]\n")
    f.write("}\n")
    f.close()

def get_group_value():
    global group_value
    if group_value == 11:
        group_value = 1
        return group_value
    else:
        return_value = group_value
        group_value = group_value + 1
        return return_value

create_json_file()

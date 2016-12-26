cmake_minimum_required(VERSION 3.6)
project(judge_client)

set(CMAKE_CXX_STANDARD 11)


set(SOURCE_FILES
    judge_client.cc
    ncalls.h
    okcalls.h
    okcalls32.h
    okcalls64.h)

add_executable(judge_client ${SOURCE_FILES})
target_link_libraries(judge_client mysqlclient)
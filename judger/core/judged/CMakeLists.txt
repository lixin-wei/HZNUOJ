cmake_minimum_required(VERSION 3.6)
project(judged)

set(CMAKE_CXX_STANDARD 11)

set(SOURCE_FILES
    judged.cc)

add_executable(judged ${SOURCE_FILES})
target_link_libraries(judged mysqlclient)
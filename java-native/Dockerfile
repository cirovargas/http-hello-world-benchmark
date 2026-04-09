FROM maven:3.9-eclipse-temurin-21 AS maven-dist

FROM ghcr.io/graalvm/native-image-community:21 AS build
COPY --from=maven-dist /usr/share/maven /usr/share/maven
ENV PATH="/usr/share/maven/bin:${PATH}"
ENV MAVEN_HOME=/usr/share/maven

WORKDIR /build
COPY pom.xml .
RUN mvn dependency:go-offline -q
COPY src ./src
RUN mvn -Pnative native:compile -DskipTests -q

FROM debian:bookworm-slim
WORKDIR /app
COPY --from=build /build/target/hello .
EXPOSE 8081
CMD ["./hello"]

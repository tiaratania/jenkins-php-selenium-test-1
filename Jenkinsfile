pipeline {
    agent any
    stages {
        stage('OWASP Dependency-Check Vulnerabilities') {
            agent any
            environment {
                NVD_API_KEY = credentials('NVD_API_KEY')
            }
            steps {
            dependencyCheck additionalArguments: ''' 
                -o './'
                -s './'
                -f 'ALL'
                --nvdApiKey $NVD_API_KEY
                --prettyPrint''', odcInstallation: 'OWASP Dependency-Check Vulnerabilities'
                dependencyCheckPublisher pattern: 'dependency-check-report.xml'
            }
        }        
        stage('Integration UI Test') {
            parallel {
                stage('Deploy') {
                    agent any
                    steps {
                        sh './jenkins/scripts/deploy.sh'
                        input message: 'Finished using the web site? (Click "Proceed" to continue)'
                        sh './jenkins/scripts/kill.sh'
                    }
                }
                stage('Headless Browser Test') {
                    agent {
                        docker {
                            image 'maven'
                            args '--platform linux/amd64 -u root --network jenkins-php-selenium-test-1_jenkins-net --entrypoint=""'
                        }
                    }
                    steps {
                        sh 'mvn -B -DskipTests clean package'
                        sh 'mvn test'
                    }
                    post {
                        always {
                            junit 'target/surefire-reports/*.xml'
                        }
                    }
                }
            }
        }
        stage('SonarQube Analysis') {
            agent any
            environment {
                SONARQUBE_TOKEN = 'sqp_b83326b318b78185e0b81d2d377e35ee175dc084'
                SONARQUBE_SCANNER_HOME = tool name: 'SonarQube Scanner'
            }
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh '''
                    ${SONARQUBE_SCANNER_HOME}/bin/sonar-scanner \
                    -Dsonar.projectKey=OWASP \
                    -Dsonar.sources=./src \
                    -Dsonar.host.url=http://sonarqube:9000 \
                    -Dsonar.login=${SONARQUBE_TOKEN}
                    '''
                }
            }
        }
    }  
}

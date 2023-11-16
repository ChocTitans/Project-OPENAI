podTemplate(containers: [
    //containerTemplate(name: 'maven', image: 'maven:3.6.3-openjdk-17-slim', command: 'cat', ttyEnabled: 'true'),
    containerTemplate(name: 'docker', image: 'docker:dind', command: '', ttyEnabled: true, privileged: true, envVars: [envVar(key: 'DOCKER_TLS_CERTDIR', value: '')]),
  ]) {

    node(POD_LABEL)
    {
        script {scannerHome = tool 'sonarqube' }

        environment {
            KUBE_NAMESPACE = 'devops-tools'

        }
        stage ('Installing Requirements')
        {
            container('docker')
            {
                script
                {

                    //sh 'dockerd-entrypoint.sh &'
                    //sh 'until docker info; do sleep 1; done'

                    sh 'apk update && apk add aws-cli'

                    sh 'apk add kustomize'
                    sh 'wget -O kubectl https://dl.k8s.io/release/$(wget -qO- https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl'
                    sh 'chmod +x kubectl'
                    sh 'mv kubectl /usr/local/bin/'
                }
            }
        }
        stage ('Clone')
        {
            git branch: 'main', changelog: false, credentialsId: 'Github-Hamza', poll: false, url: 'https://github.com/ChocTitans/OPENAI.git'
        }

        stage('Docker build & push')
        {

            container('docker')
            {
                script
                {
                    withDockerRegistry(credentialsId: 'DockerHamza', url: '')
                    {
                        sh 'docker build -t eltitans/openai:latest .'
                        sh 'docker push eltitans/openai:latest'
                    }
                }
            }
        }
    }
}
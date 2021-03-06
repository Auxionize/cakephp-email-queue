<?php
App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');
App::uses('ClassRegistry', 'Utility');

class PreviewShell extends AppShell {

    public function main() {
        Configure::write('App.baseUrl', '/');
        Router::setRequestInfo(new CakeRequest(Configure::read('App.baseUrl'), false));

        $conditions = array();
        if ($this->args) {
            $conditions['id'] = $this->args;
        }

        $emailQueue = ClassRegistry::init('EmailQueue.EmailQueue');
        $emails = $emailQueue->find('all', array(
            'conditions' => $conditions
        ));

        if (!$emails) {
            $this->out('No emails found');
            return;
        }

        $this->clear();
        foreach ($emails as $i => $email) {
            if ($i) {
                $this->out('Hit a key to continue');
                `read foo`;
                $this->clear();
            }
            $this->out("Email :" . $email['EmailQueue']['id']);
            $this->preview($email);
        }
    }

    public function preview($e) {
        $configName = $e['EmailQueue']['config'];
        $template = $e['EmailQueue']['template'];
        $layout = $e['EmailQueue']['layout'];

        $email = new CakeEmail($configName);
        $email->transport('Debug')
            ->to($e['EmailQueue']['to'])
            ->subject($e['EmailQueue']['subject'])
            ->template($template, $layout)
            ->emailFormat($e['EmailQueue']['format'])
            ->viewVars($e['EmailQueue']['template_vars']);

        list($configFrom['email'], $configFrom['name']) = @each($email->from());

        $from = Hash::merge(
            Hash::filter($configFrom),
            Hash::filter(array(
                'email' => $e['EmailQueue']['from_email'],
                'name' => $e['EmailQueue']['from_name'],
            ))
        );

        if (!empty($from)) {
            $email->from($from['email'], $from['name']);
        }

        if (isset($e['EmailQueue']['template_vars']['language'])) {
            Configure::write('Config.language', $e['EmailQueue']['template_vars']['language']);
            Router::getRequest()->params['language'] = $e['EmailQueue']['template_vars']['language'];
        }

        $return = $email->send();

        $this->out('Headers:');
        $this->hr();
        $this->out($return['headers']);
        $this->hr();
        $this->out('Content:');
        $this->hr();
        $this->out($return['message']);
        $this->hr();
//         $this->out('Data:');
//         $this->hr();
//         debug ($e['EmailQueue']['template_vars']);
//         $this->hr();
        $this->out();
    }

}

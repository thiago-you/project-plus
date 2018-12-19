<?php
namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "acionamento".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_contrato
 * @property int $colaborador_id
 * @property int $colaborador_agendamento
 * @property string $descricao
 * @property string $data
 * @property string $data_agendamento
 * @property string $telefone
 * @property int $tipo Flag que valida o tipo do evento
 * @property int $subtipo
 *
 * @property Cliente $cliente
 * @property Colaborador $colaborador
 * @property Contrato $contrato
 */
class Acionamento extends \yii\db\ActiveRecord
{
    // const para o tipo de acionamento do sistema
    CONST TIPO_SISTEMA = '0';
    // const para o subtipo de acionamento de negociacao
    CONST SUBTIPO_NEGOCIACAO = '1';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acionamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['id_cliente', 'tipo'], 'required'],
            [['id_cliente', 'id_contrato', 'colaborador_id', 'colaborador_agendamento', 'tipo', 'subtipo'], 'integer'],
            [['data', 'data_agendamento'], 'safe'],
            [['descricao'], 'string', 'max' => 250],
            [['telefone'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
        
        // valisa se o usuairo logado é admin
        if (\Yii::$app->user->id > 0) {
            $rules[] = [['colaborador_id'], 'required'];
            $rules[] = [['colaborador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colaborador::className(), 'targetAttribute' => ['colaborador_id' => 'id']];
        }
        
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_cliente' => 'Id Cliente',
            'id_contrato' => 'Id Contrato',
            'colaborador_id' => 'Colaborador ID',
            'colaborador_agendamento' => 'Colaborador Agendamento',
            'descricao' => 'Descricao',
            'data' => 'Data',
            'data_agendamento' => 'Data Agendamento',
            'telefone' => 'Telefone',
            'tipo' => 'Tipo',
            'subtipo' => 'Subtipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColaborador()
    {
        // valida o colaborador
        if ($this->colaborador_id == 0) {
            return Colaborador::getAdminUser();            
        }
        
        return $this->hasOne(Colaborador::className(), ['id' => 'colaborador_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColaboradorAgendamento()
    {   
        return $this->hasOne(Colaborador::className(), ['id' => 'colaborador_agendamento']);
    }
    
    /**
     * Retorna os tipos de acionamento
     */
    public static function getTipos() 
    {
        return [
            '1' => 'Agendamento',
            '2' => 'Contato com o cliente',
            '3' => 'Outros',
        ];        
    }
    
    /**
     * Retorna a descrição do tipo
     */
    public function getTipo()
    {
        // busca a lista de tipos
        $tipos = Acionamento::getTipos();
 
        // adiciona o tipo de sistema
        $tipos[0] = 'Sistema';
        
        // retorna a descrição do tipo atual
        return $tipos[$this->tipo];
    }
    
    /**
     * Retorna os subtipos de acionamento
     */
    public static function getSubtipos()
    {
        $subtipos = [
            '1' => 'Negociação',
            '2' => 'Alega pagamento',            
            '3' => 'Cadastro insuficiente',
            '4' => 'Cliente desconhecido',
            '5' => 'Contato receptivo',
            '6' => 'Cobrança externa',
            '7' => 'Despesa',
            '8' => 'Devolução',
            '9' => 'Envio de e-mail para o cliente',
            '10' => 'Envio de e-mail para a carteira',
            '11' => 'Envio de carta',
            '12' => 'Falcido',
            '13' => 'Mudou-se / Saiu da empresa',
            '14' => 'Não anotou recado',
            '15' => 'Não reconhece débito',
            '16' => 'Notificação',
            '17' => 'Observação',
            '18' => 'Protesto',
            '19' => 'Recado com terceiros',
            '20' => 'Recado na residência',
            '21' => 'Alega Pagamento',
            '22' => 'Recado no trabalho',
            '23' => 'Reclamação',
            '24' => 'Sem condição de pagamento',
            '25' => 'Sem contato',
            '26' => 'Suspeita de fraude',
            '27' => 'Suspensão da cobrança',
            '28' => 'Telefone desligado',
            '29' => 'Telefone não atende',
            '30' => 'Telfone não existe',
            '31' => 'Telefone ocupado',
            '32' => 'Outros',
        ];
        
        // ordena o array pela nome
        asort($subtipos);
        
        return $subtipos;
    }
    
    /**
     * Retorna a descrição do subtipo
     */
    public function getSubtipo()
    {
        // retorna a descrição do tipo atual
        return Acionamento::getSubtipos()[$this->subtipo];
    }
    
    /** 
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // formata as datas
        if (!empty($this->data)) {
            $this->data = Helper::dateUnmask($this->data, Helper::DATE_DEFAULT, true);
        } else {
            $this->data = date('Y-m-d H:i:s');
        }
        if (!empty($this->data_agendamento)) {
            $this->data_agendamento = Helper::dateUnmask($this->data_agendamento, Helper::DATE_DEFAULT, true);
        }
        
        return parent::beforeSave($insert);
    }
    
    /**
     * Seta um acionamento automatico
     */
    public static function setAcionamento($params = []) 
    {
        // cria o novo acionamento
        $acionamento = new Acionamento();
        
        // seta os dados do acionamento
        $acionamento->id_cliente = $params['id_cliente'];
        $acionamento->id_contrato = $params['id_contrato'];
        $acionamento->colaborador_id = \Yii::$app->user->id;
        $acionamento->descricao = $params['descricao'];
        $acionamento->tipo = $params['tipo'];
        $acionamento->subtipo = isset($params['subtipo']) ? $params['subtipo'] : 0;
        $acionamento->data_agendamento = isset($params['data_agendamento']) ? $params['data_agendamento'] : '';
        $acionamento->colaborador_agendamento = isset($params['colaborador_agendamento']) ? $params['colaborador_agendamento'] : '';
        
        // salva o acionamento
        return $acionamento->save();
    }
}









